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

    protected $aplus = 0;

    public function __construct(Engineer $engineer)
    {
        $this->engineer = $engineer;
    }

    public function handle(): void
    {
        $this->gradeMetrics();
        $this->gradeSkills();

        $scores = collect($this->scores);
        $above = $scores->filter(fn ($g) => $g >= 0);
        $below = $scores->filter(fn ($g) => $g < 0);
        $perfect = $below->count() == 0;
        $score = $perfect ? $above->sum() : $below->sum();

        $this->engineer->score = $score;
        $this->engineer->grade = score_to_grade($score, $this->aplus);
        $this->engineer->graded_at = now();
        $this->engineer->save();
    }

    protected function gradeMetrics()
    {
        $metrics = $this->engineer->getWatchedMetrics();

        // grant ±1 point for each 20% diff
        foreach ($metrics as $metric) {
            $this->aplus += 1;
            $this->scores[] = round(($metric->deviation) / 20, 0);
        }
    }

    protected function gradeSkills()
    {
        // grant ±2 points for each level
        $this->aplus += 2;
        $skillset = $this->engineer->skillset;
        $this->scores[] = 2 * ($skillset->score - $skillset->level);
    }
}
