<?php

namespace App\Jobs\Graders;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sassnowski\Venture\WorkflowableJob;
use Sassnowski\Venture\WorkflowStep;

class GradeTeam implements WorkflowableJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, WorkflowStep;

    protected Team $team;

    protected $scores = [];

    protected $aplus = 0;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function handle(): void
    {
        if (! $this->team->isCluster()) {
            $this->gradeSkills();
            $this->gradeMetrics();

            $scores = collect($this->scores);
            $above = $scores->filter(fn ($g) => $g >= 0);
            $below = $scores->filter(fn ($g) => $g < 0);
            $perfect = $below->count() == 0;
            $score = $perfect ? $above->sum() : $below->sum();

            $this->team->score = $score;
            $this->team->grade = score_to_grade($score, $this->aplus);
            $this->team->graded_at = now();
            $this->team->save();
        }
    }

    protected function gradeMetrics()
    {
        // find avg scores for team
        $averages = [];
        $counts = [];

        foreach ($this->team->engineers as $engineer) {
            $metrics = $engineer->getWatchedMetrics();

            foreach ($metrics as $m) {
                if (! isset($averages[$m->metric])) {
                    $averages[$m->metric] = 0;
                    $counts[$m->metric] = 0;
                }

                $averages[$m->metric] += $m->value;
                $counts[$m->metric] += 1;
            }
        }

        $metrics = [];

        foreach ($averages as $m => $v) {
            $value = bcdiv($v / $counts[$m], 1, 2);

            $metrics[] = $this->team->metrics()->updateOrCreate([
                'metric' => $m,
                'date' => now()->toDateString(),
            ], [
                'value' => $value,
                'source' => 'computed',
            ]);
        }

        // grant ±1 point for each 20% diff
        foreach ($metrics as $metric) {
            $this->aplus += 1;
            $this->scores[] = round(($metric->deviation) / 20, 0);
        }
    }

    protected function gradeSkills()
    {
        // find max scores for team
        $scores = [];
        $engineersCount = $this->team->engineers->count();

        foreach ($this->team->engineers as $engineer) {
            foreach ($engineer->skillset->getSkills(false) as $i => $s) {
                $scores[$i] = ($scores[$i] ?? 0) + $s;
            }
        }

        foreach ($scores as $i => $s) {
            $scores[$i] = bcdiv($s / $engineersCount, 1, 2);
        }

        $skillset = $this->team->skillsets()->updateOrCreate([
            'date' => now()->toDateString(),
            'source' => 'grader',
        ], $scores);

        $skillset->refresh();

        // grant ±2 points for each level
        $this->aplus += 2;
        $this->scores[] = 2 * ($skillset->score - $skillset->level);
    }
}
