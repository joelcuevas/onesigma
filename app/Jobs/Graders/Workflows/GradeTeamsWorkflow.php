<?php

namespace App\Jobs\Graders\Workflows;

use App\Jobs\Graders\GradeTeam;
use Illuminate\Support\Collection;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class GradeTeamsWorkflow extends AbstractWorkflow
{
    private Collection $teams;

    public function __construct(Collection $teams)
    {
        $this->teams = $teams;
    }

    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('grade-teams');

        foreach ($this->teams as $team) {
            $workflow
                ->addWorkflow(
                    new GradeEngineersWorkflow($team->engineers),
                    dependencies: [],
                    id: 'engineers-'.$team->id
                )
                ->addJob(
                    new GradeTeam($team),
                    dependencies: ['engineers-'.$team->id],
                    id: 'team-'.$team->id,
                );
        }

        return $workflow;
    }
}
