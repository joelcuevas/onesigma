<?php

namespace App\Jobs\Graders\Workflows;

use App\Jobs\Graders\GradeCluster;
use App\Jobs\Graders\GradeTeam;
use App\Models\Team;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class GradeClustersWorkflow extends AbstractWorkflow
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('grade-clusters');

        // grade children teams
        $teams = $this->team->children()->withoutClusters()->get();

        $workflow->addWorkflow(
            new GradeTeamsWorkflow($teams), 
            id: 'subteams',
        );

        // grade children clusters
        $clusters = $this->team->children()->onlyClusters()->get();
        $wait = [];

        foreach ($clusters as $cluster) {
            $id = 'cluster-'.$cluster->id;
            $wait[] = $id;

            $workflow->addWorkflow(
                new GradeClustersWorkflow($cluster),
                dependencies: ['subteams'],
                id: $id,
            );
        }

        // grade self
        if ($this->team->isCluster()) {
            $workflow->addJob(
                new GradeCluster($this->team), 
                dependencies: $wait,
            );
        } else {
            $workflow->addWorkflow(
                new GradeTeamsWorkflow(collect([$this->team])), 
                dependencies: $wait,
            );
        }

        return $workflow;
    }
}
