<?php

namespace App\Jobs\Graders\Workflows;

use App\Jobs\Graders\GradeEngineer;
use Illuminate\Support\Collection;
use Sassnowski\Venture\AbstractWorkflow;
use Sassnowski\Venture\WorkflowDefinition;

class GradeEngineersWorkflow extends AbstractWorkflow
{
    private Collection $engineers;

    public function __construct(Collection $engineers)
    {
        $this->engineers = $engineers;
    }

    public function definition(): WorkflowDefinition
    {
        $workflow = $this->define('grade-engineers');

        foreach ($this->engineers as $engineer) {
            $workflow->addJob(
                new GradeEngineer($engineer),
                id: 'eng-'.$engineer->id,
            );
        }

        return $workflow;
    }
}
