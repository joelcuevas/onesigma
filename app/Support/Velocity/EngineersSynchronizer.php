<?php

namespace App\Support\Velocity;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Team;
use App\Models\Engineer;
use App\Models\Enums\TeamRole;

class EngineersSynchronizer
{
    protected $teams;

    protected $teamParents;

    protected $teamsTree;

    protected $baseUrl;

    protected $bearerToken; 
    
    public function __construct()
    {
        $this->baseUrl = 'https://api.velocity.codeclimate.com/v1/teams';
        $this->bearerToken = config('services.velocity.token');

        $this->period = now(); 
    }

    public function sync()
    {
        $this->teams = $this->fetchTeams();
        $this->fetchTeamParents();

        // @to-do: add more roots
        // 175269 = consumer services
        $teamRoots = [175269];

        foreach ($teamRoots as $root) {
            $this->teamsTree[$root] = $this->buildTeamsTree($this->teams, $root);
        }

        $this->insertTeamsAndEngineers($this->teamsTree);
    }

    protected function fetchTeams($url = null)
    {
        $isRoot = $url === null;

        $teams = collect([]);
        $url = $url ?? $this->baseUrl;

        $response = $this->cachedRequest($url);

        if ($response) {
            $teams = collect($response['data']);
            
            if (isset($response['links']['next'])) {
                $nested = $this->fetchTeams($response['links']['next']);
                $teams = $teams->merge($nested);
            }
        }

        return $teams;
    }

    protected function fetchTeamParents()
    {
        $teamParents = [];

        foreach ($this->teams as $team) {
            $url = $this->baseUrl.'/'.$team['id'].'/parent';
            $response = $this->cachedRequest($url);

            if ($response) {
                $parent = $response['data']['id'] ?? null;
                
                $teamParents[$team['id']] = [
                    'id' => $team['id'],
                    'parent_id' => $parent,
                    'is_root' => $parent == null,
                ];
            }
        }

        $this->teamParents = collect($teamParents);
    }

    protected function buildTeamsTree($teams, $root)
    {
        $team = $this->teamParents[$root];

        $source = $this->teams->where('id', $root)->first();
        $team['name'] = $source['attributes']['name'];

        $nested = $this->teamParents
            ->filter(fn($t) => $t['parent_id'] == $root)
            ->map(fn($t) => $this->buildTeamsTree($teams, $t['id']));

        if (count($nested)) {
            $team['nested'] = $nested;
        }

        return collect($team);
    }

    protected function insertTeamsAndEngineers($teamsTree)
    {
        $teamIds = [];

        foreach ($teamsTree as $tree) {
            $team = Team::firstOrCreate([
                'velocity_id' => $tree['id'],
            ], [
                'name' => $tree['name'],
                'is_root' => $tree['is_root'],
            ]);

            $teamIds[] = $team->id;

            if (isset($tree['nested'])) {
                $nestedIds = $this->insertTeamsAndEngineers($tree['nested']);
                $team->nestedTeams()->syncWithPivotValues($nestedIds, ['role' => null]);
            }

            $this->fetchAndInsertTeamEngineers($team);
        }

        return $teamIds;
    }

    protected function fetchAndInsertTeamEngineers($team)
    {
        $engineerIds = [];

        $url = $this->baseUrl."/{$team->velocity_id}/people";
        $response = $this->cachedRequest($url);

        if ($response) {
            foreach ($response['data'] as $eng) {
                $email = null;
                $is_internal = true;

                if (! Str::endsWith($eng['attributes']['email'], '@users.noreply.github.com')) {
                    $email = $eng['attributes']['email'];
                }

                if (! $email || ! Str::endsWith($email, config('onesigma.email_domain'))) {
                    $is_internal = false;
                }

                $engineer = Engineer::firstOrCreate([
                    'velocity_id' => $eng['id'],
                ], [
                    'name' => $eng['attributes']['name'],
                    'email' => $email,
                    'github_email' => $eng['attributes']['email'],
                    'is_internal' => $is_internal,
                ]);

                $engineerIds[] = $engineer->id;
            }
        }

        $sync = [];

        foreach ($engineerIds as $id) {
            $e = $team->members->where('id', $id)->first();
            
            $sync[$id] = [
                'role' => $e?->pivot->role ?? TeamRole::Engineer->value,
                'is_locked' => true,
            ];
        }

        $team->members()->sync($sync);
    }

    protected function cachedRequest($url)
    {
        $hash = 'request-cache.'.md5($url);

        return Cache::remember($hash, 10000, function() use ($url) {
            $response = Http::withToken($this->bearerToken)->get($url);
            $body = null;
        
            if ($response->ok()) {
                $body = json_decode($response->getBody()->getContents(), true);
            }

            return $body;
        });
    }
}