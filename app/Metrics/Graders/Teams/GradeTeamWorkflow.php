<?php

namespace App\Metrics\Graders\Teams;

use App\Metrics\Graders\Engineers\GradeEngineersWorkflow;
use App\Models\Team;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class GradeTeamWorkflow extends AbstractWorkflow
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('grade-team');
        $team = $this->team;
        $dependencies = [];

        if ($team->isCluster()) {
            $subteams = $team->children()->active()->get();
            $wait = [];

            foreach ($subteams as $subteam) {
                $id = 'subteam-'.$subteam->id;
                $dependencies[] = $id;

                $workflow->addWorkflow(
                    new GradeTeamWorkflow($subteam),
                    id: $id,
                );
            }
        } else {
            $id = 'engineers-'.$team->id;
            $dependencies[] = $id;

            $workflow->addWorkflow(
                new GradeEngineersWorkflow($team->engineers),
                id: $id,
            );
        }

        $workflow->addJob(
            new GradeTeam($team),
            dependencies: $dependencies,
        );

        return $workflow;
    }
}
