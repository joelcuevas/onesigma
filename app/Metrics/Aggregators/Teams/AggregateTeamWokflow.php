<?php

namespace App\Metrics\Aggregators\Teams;

use App\Models\Team;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class AggregateTeamWokflow extends AbstractWorkflow
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('aggregate-teams-recursive');
        $dependencies = [];

        if ($this->team->isCluster()) {
            $subteams = $this->team->children()->active()->get();
            $wait = [];

            foreach ($subteams as $subteam) {
                $id = 'team-'.$subteam->id;
                $dependencies[] = $id;

                $workflow->addWorkflow(
                    new AggregateTeamWokflow($subteam),
                    id: $id,
                );
            }
        }

        $workflow->addJob(
            new AverageTeam($this->team),
            dependencies: $dependencies,
        );

        return $workflow;
    }
}
