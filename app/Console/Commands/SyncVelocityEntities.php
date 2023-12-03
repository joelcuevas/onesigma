<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Team;
use Http;
use Cache;

class SyncVelocityEntities extends Command
{
    protected $signature = 'velocity:sync-entities {--cs}';

    protected $description = 'Sync Velocity\'s teams and users';

    protected $teams;

    protected $teamParents;

    protected $teamsTree;

    protected $teamsUrl = 'https://api.velocity.codeclimate.com/v1/teams';

    protected $bearerToken;

    public function handle()
    {
        $this->bearerToken = config('services.velocity.token');

        $roots = [];

        if ($this->option('cs')) {
            $roots = [175269];
        }

        $this->line('Fetching teams');
        $this->teams = $this->fetchTeams();

        $this->line('Building teams tree');
        $this->teamParents = $this->fetchTeamParents($this->teams);

        foreach ($roots as $root) {
            $this->teamsTree[$root] = $this->buildTeamsTree($this->teams, 175269);
        }

        $this->insertTeams($this->teamsTree);

        //dd(json_decode(json_encode($this->teamsTree)));
    }

    protected function fetchTeams($url = null)
    {
        $isRoot = $url === null;

        if ($isRoot && Cache::has('velocity.teams')) {
            $this->line('  Retrieved from cache!');

            return Cache::get('velocity.teams');
        }

        $teams = collect([]);
        $url = $url ?? $this->teamsUrl;

        $this->line('  Retrieving from source...');
        $response = Http::withToken($this->bearerToken)->get($url);

        if ($response->ok()) {
            $teams = collect($response['data']);
            
            if (isset($response['links']['next'])) {
                $nested = $this->fetchTeams($response['links']['next']);
                $teams = $teams->merge($nested);
            }
        }

        if ($isRoot) {
            Cache::put('velocity.teams', $teams, 3600);
        }

        return $teams;
    }

    protected function fetchTeamParents($teams)
    {
        if (Cache::has('velocity.team-parents')) {
            $this->line('  Retrieved from cache!');

            return Cache::get('velocity.team-parents');
        }

        $teamParents = collect([]);

        $this->withProgressBar($teams, function ($team) use (&$teamParents) {
            $url = $this->teamsUrl.'/'.$team['id'].'/parent';
            $response = Http::withToken($this->bearerToken)->get($url);
            $parent = $response['data']['id'] ?? null;
            
            $teamParents[$team['id']] = [
                'id' => $team['id'],
                'parent_id' => $parent,
            ];
        });

        Cache::put('velocity.team-parents', $teamParents, 3600);

        return collect($teamParents);
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

    protected function insertTeams($teams)
    {
        $ids = [];

        foreach ($teams as $tree) {
            $team = Team::firstOrCreate([
                'velocity_id' => $tree['id'],
            ], [
                'name' => $tree['name'],
            ]);

            $ids[] = $team->id;

            if (isset($tree['nested'])) {
                $nestedIds = $this->insertTeams($tree['nested']);
                $team->setNestedTeams($nestedIds);
            }

            $team->touch();
        }

        return $ids;
    }
}
