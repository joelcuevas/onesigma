<?php

namespace App\Metrics\Ingestors\Velocity;

use App\Models\Engineer;
use App\Models\Position;
use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Sassnowski\Venture\WorkflowableJob;
use Sassnowski\Venture\WorkflowStep;

class VelocityEngineers implements WorkflowableJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WorkflowStep;

    protected $baseUrl;

    protected $bearerToken;

    protected $se1;

    public function __construct()
    {
        $this->baseUrl = 'https://api.velocity.codeclimate.com/v1';
        $this->bearerToken = config('services.velocity.token');
        $this->se1 = Position::firstWhere('code', 'SE1')?->id;
    }

    public function handle(): void
    {
        if ($this->bearerToken) {
            $this->fetchPage($this->baseUrl.'/people');
        }
    }

    public function fetchPage($url)
    {
        $peopleResponse = $this->cachedRequest($url);

        if ($peopleResponse) {
            $people = collect($peopleResponse['data']);

            $people->map(function ($p) {
                // nothing to do if the engineer exists
                $exists = Engineer::query()
                    ->whereIdentity('velocity', $p['id'])
                    ->exists();

                if ($exists) {
                    return;
                }

                // get engineer teams
                $url = $this->baseUrl.'/people/'.$p['id'].'/teams';
                $teamsResponse = $this->cachedRequest($url);
                $team = null;

                if (count($teamsResponse['data'])) {
                    $team = Team::query()
                        ->whereIdentity('velocity', $teamsResponse['data'][0])
                        ->first();
                }

                // we have a lot of idle users in velocity
                // only import the ones who are part of a team
                if ($team) {
                    $engineer = Engineer::query()
                        ->whereIdentity('velocity', $p['id'])
                        ->first();

                    // create engineer if it doesn't exists
                    if (is_null($engineer)) {
                        $engineer = Engineer::create([
                            'name' => $p['attributes']['name'],
                            'email' => $p['attributes']['email'],
                            'position_id' => $this->se1,
                        ]);

                        $engineer->identities()->create([
                            'source' => 'velocity',
                            'source_id' => $p['id'],
                            'source_email' => $p['attributes']['email'],
                        ]);

                        $engineer->skillsets()->create();

                        // link engineer to team only on creation
                        $team->engineers()->syncWithoutDetaching($engineer);
                    }
                }
            });

            // fetch next results page
            if (isset($peopleResponse['links']['next'])) {
                $this->fetchPage($peopleResponse['links']['next']);
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
