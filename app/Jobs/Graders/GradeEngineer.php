<?php

namespace App\Jobs\Graders;

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
        $this->gradeMetrics();
        $this->gradeSkills();

        $this->engineer->updateGrade($this->scores);
    }

    protected function gradeMetrics()
    {
        $metrics = $this->engineer->getWatchedMetrics();

        foreach ($metrics as $metric) {
            $this->scores[] = $metric->getScoreForGrader();
        }
    }

    protected function gradeSkills()
    {
        $this->scores[] = $this->engineer->skillset->getScoreForGrader();
    }
}
