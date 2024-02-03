<?php

namespace App\Metrics\Graders;

use App\Metrics\Graders\Teams\GradeTeamWorkflow;
use App\Models\Team;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class GradersWorkflow extends AbstractWorkflow
{
    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('metric-graders');

        // aggregate teams metrics

        foreach (Team::isRoot()->get() as $team) {
            GradeTeamWorkflow::start($team);
        }

        return $workflow;
    }
}
