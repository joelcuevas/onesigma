<?php

namespace App\Metrics\Aggregators;

use App\Metrics\Aggregators\Teams\AggregateTeamWokflow;
use App\Models\Team;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class AggregatorsWorkflow extends AbstractWorkflow
{
    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('aggregators');

        // aggregate teams metrics

        foreach (Team::isRoot()->get() as $team) {
            AggregateTeamWokflow::start($team);
        }

        return $workflow;
    }
}
