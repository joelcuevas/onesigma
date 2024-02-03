<?php

namespace App\Metrics\Graders\Engineers;

use App\Models\Engineer;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sassnowski\Venture\WorkflowableJob;
use Sassnowski\Venture\WorkflowStep;

class GradeEngineer implements WorkflowableJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WorkflowStep;

    protected Engineer $engineer;

    protected $scores = [];

    public function __construct(Engineer $engineer)
    {
        $this->engineer = $engineer;
    }

    public function handle(): void
    {
        $metrics = $this->engineer->getWatchedMetrics();

        foreach ($metrics as $metric) {
            $this->scores[] = $metric->getScoreForGrader();
        }

        $this->scores[] = $this->engineer->skillset->getScoreForGrader();

        $this->engineer->updateGrade($this->scores);
    }
}
