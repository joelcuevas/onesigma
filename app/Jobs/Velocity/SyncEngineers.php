<?php

namespace App\Jobs\Velocity;

use App\Models\Engineer;
use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SyncEngineers implements ShouldQueue
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
        $this->fetchPage($this->baseUrl.'/people');
    }

    public function fetchPage($url)
    {
        $response = $this->cachedRequest($url);
        $people = collect([]);

        if ($response) {
            $people = collect($response['data']);

            $people = $people->map(function ($p) {
                // get engineer teams
                $url = $this->baseUrl.'/people/'.$p['id'].'/teams';
                $response = $this->cachedRequest($url);

                if (count($response['data'])) {
                    $p['_team_'] = $response['data'][0];
                }

                $teamId = $p['_team_']['id'] ?? null;

                if ($teamId) {
                    // create team if it doesn't exists
                    $team = Team::query()
                        ->whereIdentity('velocity', $teamId)
                        ->first();

                    if (is_null($team)) {
                        $team = Team::create([
                            'name' => $p['_team_']['attributes']['name'],
                            'parent_id' => 1,
                        ]);

                        $team->identities()->create([
                            'source' => 'velocity',
                            'source_id' => $teamId,
                        ]);
                    }

                    // create engineer if it doesn't exists
                    $engineerId = $p['id'];

                    $engineer = Engineer::query()
                        ->whereIdentity('velocity', $engineerId)
                        ->first();

                    if (is_null($engineer)) {
                        $engineer = Engineer::create([
                            'name' => $p['attributes']['name'],
                            'email' => $p['attributes']['email'],
                        ]);

                        $engineer->identities()->create([
                            'source' => 'velocity',
                            'source_id' => $engineerId,
                            'source_email' => $p['attributes']['email'],
                        ]);

                        $engineer->skillsets()->create();
                    }

                    // link engineer to team
                    $team->engineers()->syncWithoutDetaching($engineer);
                }

                return $p;
            });

            // fetch next results page
            if (isset($response['links']['next'])) {
                $nested = $this->fetchPage($response['links']['next']);
                $people = $people->merge($nested);
            }
        }

        return $people;
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
