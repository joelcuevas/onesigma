<?php

namespace Tests\Feature;

use App\Jobs\Velocity\SyncEngineers;
use App\Jobs\Velocity\SyncMetrics;
use App\Jobs\Velocity\SyncTeams;
use App\Models\Engineer;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VelocityTest extends TestCase
{
    use RefreshDatabase;

    public function test_velocity_engineers_and_metrics_are_synced()
    {
        $this->fakeVelocityPayloads();

        Team::factory()
            ->create([
                'name' => 'Velocity Team 1',
            ])
            ->identities()->create([
                'source' => 'velocity',
                'source_id' => '1669301',
                'context' => ['name' => 'Prev'],
            ]);

        SyncTeams::dispatch();

        $team1 = Team::query()
            ->whereIdentity('velocity', '1669301')
            ->first();

        $context = $team1->identities
            ->where('source', 'velocity')
            ->first()
            ->context;

        $this->assertNotNull($team1);
        $this->assertEquals(['name' => 'Velocity Team 1'], $context);

        $team2 = Team::query()
            ->whereIdentity('velocity', '1669302')
            ->first();

        $this->assertNotNull($team2);

        SyncEngineers::dispatch();

        $engineer = Engineer::where('email', 'velocity1@engineer.com')->first();
        $team = $engineer->teams->first();

        $this->assertNotNull($engineer);
        $this->assertEquals('Velocity Team 1', $team->name);

        $identity = $engineer->identities->where('source', 'velocity')->first();
        $this->assertEquals($engineer->id, $identity->identifiable->id);
        $this->assertEquals('17024891', $identity->source_id);
        $this->assertEquals('velocity1@engineer.com', $identity->source_email);

        $this->assertEquals(0, $engineer->getLatestMetrics()->count());

        SyncMetrics::dispatch();

        $metrics = $engineer->getLatestMetrics();
        $metricsCount = count(config('onesigma.metrics.velocity'));

        $this->assertEquals($metricsCount, $metrics->count());

        foreach (config('onesigma.metrics.watching') as $w) {
            $this->assertDatabaseHas('metrics', [
                'metricable_type' => 'engineer',
                'metricable_id' => $engineer->id,
                'metric' => $w,
                'value' => 100,
            ]);
        }
    }

    public function test_sync_velocity_command_runs()
    {
        $this->fakeVelocityPayloads();

        Artisan::call('velocity:sync');

        $this->assertDatabaseHas('engineers', ['email' => 'velocity1@engineer.com']);
        $this->assertDatabaseHas('teams', ['name' => 'Velocity Team 1']);

        $engineer = Engineer::where('email', 'velocity1@engineer.com')->first();
        $metric = config('onesigma.metrics.watching')[0];

        $this->assertDatabaseHas('metrics', [
            'metricable_type' => 'engineer',
            'metricable_id' => $engineer->id,
            'metric' => $metric,
            'value' => 100,
        ]);
    }

    protected function fakeVelocityPayloads()
    {
        $seq = Http::fakeSequence()
            ->push($this->teamsPayload(1), 200)
            ->push($this->teamsPayload(2), 200)
            ->push($this->peoplePayload(1), 200)
            ->push($this->peopleTeamsPayload(), 200)
            ->push($this->peoplePayload(2), 200)
            ->push($this->peopleTeamsPayload(), 200);

        foreach (config('onesigma.metrics.velocity') as $m) {
            $seq->push($this->metricPayload(), 200);
        }
    }

    protected function teamsPayload($page = 1)
    {
        $pagination = '';

        if ($page == 1) {
            $pagination = '
                , "links": {
                    "next": "https://api.velocity.codeclimate.com/v1/teams?page%5Bnumber%5D=2&page%5Bsize%5D=50"
                }
            ';
        }

        return '
            {
                "data": [
                    {
                        "id": "166930'.$page.'",
                        "type": "teams",
                        "links": {
                            "self": "https://api.velocity.codeclimate.com/v1/teams/166930'.$page.'"
                        },
                        "attributes": {
                            "name": "Velocity Team '.$page.'",
                            "createdAt": "2023-08-10T22:58:28Z",
                            "sync": null
                        },
                        "relationships": {
                            "people": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/166930'.$page.'/people"
                                }
                            },
                            "teams": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/166930'.$page.'/teams"
                                }
                            },
                            "parent": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/166930'.$page.'/parent"
                                }
                            }
                        }
                    }
                ]
                '.$pagination.'
            }
        ';
    }

    protected function peoplePayload($page = 1)
    {
        $pagination = '';

        if ($page == 1) {
            $pagination = '
                , "links": {
                    "next": "https://api.velocity.codeclimate.com/v1/people?page%5Bnumber%5D=2&page%5Bsize%5D=50"
                }
            ';
        }

        return '
            {
                "data": [
                    {
                        "id": "1702489'.$page.'",
                        "type": "people",
                        "links": {
                            "self": "https://api.velocity.codeclimate.com/v1/people/1702489'.$page.'"
                        },
                        "attributes": {
                            "name": "Velocity Engineer '.$page.'",
                            "email": "velocity'.$page.'@engineer.com",
                            "createdAt": "2023-03-02T19:02:54.773Z",
                            "hasUser": false,
                            "endDate": null,
                            "startDate": null,
                            "visible": false,
                            "lastActiveAt": null
                        },
                        "relationships": {
                            "teams": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/people/1702489'.$page.'/teams"
                                }
                            }
                        }
                    }
                ]
                '.$pagination.'
            }
        ';
    }

    protected function peopleTeamsPayload()
    {
        return '
            {
                "data": [
                    {
                        "id": "1669301",
                        "type": "teams",
                        "links": {
                            "self": "https://api.velocity.codeclimate.com/v1/teams/1669301"
                        },
                        "attributes": {
                            "name": "Velocity Team",
                            "createdAt": "2023-10-05T23:02:23Z",
                            "sync": {
                                "synchronizedAt": "2024-01-21T03:11:37Z",
                                "source": {
                                    "type": "github",
                                    "url": "https://github.com/digitaltitransversal"
                                }
                            }
                        },
                        "relationships": {
                            "people": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/1669301/people"
                                }
                            },
                            "teams": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/1669301/teams"
                                }
                            },
                            "parent": {
                                "links": {
                                    "related": "https://api.velocity.codeclimate.com/v1/teams/1669301/parent"
                                }
                            }
                        }
                    }
                ]
            }
        ';
    }

    protected function metricPayload()
    {
        return '
            {
                "data": [
                    {
                        "group": {
                            "owner_id": 17024891,
                            "owner_name": "Velocity Engineer 1",
                            "owner_avatar_url": "https://avatars.githubusercontent.com/u/17024891?v=4"
                        },
                        "values": [
                            {
                                "value": 100.0,
                                "period": "2024-01-14T00:00:00.000-06:00",
                                "formatted_value": "100%",
                                "short_formatted_value": "100%",
                                "links": {
                                    "drilldown": "https://velocity.codeclimate.com/drilldown"
                                }
                            }
                        ]
                    },
                    {
                        "group": {
                            "owner_id": 17024892,
                            "owner_name": "Velocity Engineer 2",
                            "owner_avatar_url": "https://avatars.githubusercontent.com/u/17024892?v=4"
                        },
                        "values": [
                            {
                                "value": 100.0,
                                "period": "2024-01-14T00:00:00.000-06:00",
                                "formatted_value": "100%",
                                "short_formatted_value": "100%",
                                "links": {
                                    "drilldown": "https://velocity.codeclimate.com/drilldown"
                                }
                            }
                        ]
                    }
                ]
            }
        ';
    }
}
