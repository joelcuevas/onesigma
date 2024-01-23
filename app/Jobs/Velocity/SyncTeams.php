<?php

namespace App\Jobs\Velocity;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SyncTeams implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl;

    protected $bearerToken;

    public function __construct()
    {
        $this->baseUrl = 'https://api.velocity.codeclimate.com/v1';
        $this->bearerToken = config('services.velocity.token');
    }

    public function handle(): void
    {
        $this->fetchPage($this->baseUrl.'/teams');
    }

    public function fetchPage($url)
    {
        $response = $this->cachedRequest($url);

        if ($response) {
            $teams = collect($response['data']);

            $teams->map(function ($t) {
                $teamId = $t['id'];
                $name = $t['attributes']['name'];

                $team = Team::query()
                    ->whereIdentity('velocity', $teamId)
                    ->first();

                if (is_null($team)) {
                    $team = Team::create([
                        'name' => $name,
                        'parent_id' => null,
                    ]);

                    $team->identities()->create([
                        'source' => 'velocity',
                        'source_id' => $teamId,
                        'context' => ['name' => $name],
                    ]);
                } else {
                    $velocity = $team->identities
                        ->where('source', 'velocity')
                        ->first();

                    $context = $velocity->context ?? [];
                    $context['name'] = $name;
                    $velocity->context = $context;
                    $velocity->save();
                }
            });

            // fetch next results page
            if (isset($response['links']['next'])) {
                $this->fetchPage($response['links']['next']);
            }
        }
    }

    protected function cachedRequest($url)
    {
        $hash = 'request-cache.'.md5($url);

        return Cache::remember($hash, 10000, function () use ($url) {
            $response = Http::withToken($this->bearerToken)->get($url);
            $body = null;

            if ($response->ok()) {
                $body = json_decode($response->getBody()->getContents(), true);
            }

            return $body;
        });
    }
}
