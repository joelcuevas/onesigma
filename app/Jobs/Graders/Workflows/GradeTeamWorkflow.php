<?php

namespace App\Jobs\Graders\Workflows;

use App\Jobs\Graders\GradeTeam;
use Illuminate\Support\Collection;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;
use App\Models\Team;

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
