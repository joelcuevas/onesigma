<?php

namespace App\Metrics\Ingestors;

use App\Metrics\Ingestors\Velocity\VelocityEngineers;
use App\Metrics\Ingestors\Velocity\VelocityMetrics;
use App\Metrics\Ingestors\Velocity\VelocityTeams;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class IngestorsWorkflow extends AbstractWorkflow
{
    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('ingestors');

        // ingest velocity metrics

        $workflow->addJob(
            new VelocityTeams(),
        )
            ->addJob(
                new VelocityEngineers(),
                dependencies: [VelocityTeams::class],
            )
            ->addJob(
                new VelocityMetrics(),
                dependencies: [VelocityEngineers::class],
            );

        return $workflow;
    }
}
