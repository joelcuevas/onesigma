<?php

namespace App\Metrics;

use App\Metrics\Aggregators\AggregatorsWorkflow;
use App\Metrics\Graders\GradersWorkflow;
use App\Metrics\Ingestors\IngestorsWorkflow;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class MetricsWorkflow extends AbstractWorkflow
{
    public function definition(): WorkflowDefinition
    {
        return $this->define('metrics')
            ->addWorkflow(
                new IngestorsWorkflow(),
            )
            ->addWorkflow(
                new AggregatorsWorkflow(),
                dependencies: [IngestorsWorkflow::class],
            )
            ->addWorkflow(
                new GradersWorkflow(),
                dependencies: [AggregatorsWorkflow::class],
            );
    }
}
