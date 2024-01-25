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

    protected $children;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function handle(): void
    {
        if ($this->team->isCluster()) {
            $this->children = $this->team->children()->active()->get();
        } else {
            $this->children = $this->team->engineers()->get();
        }

        $this->gradeSkills();
        $this->gradeMetrics();

        $this->team->setGrade($this->scores);
    }

    protected function gradeMetrics()
    {
        $averages = [];
        $counts = [];

        if ($this->children->count()) {
            // average engineers metrics
            foreach ($this->children as $child) {
                $metrics = $child->getWatchedMetrics();

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

            // create metrics for the team
            foreach ($averages as $m => $v) {
                $value = round($v / $counts[$m], 0);

                $metrics[] = $this->team->metrics()->updateOrCreate([
                    'metric' => $m,
                    'date' => now()->toDateString(),
                ], [
                    'value' => $value,
                    'source' => 'computed',
                ]);
            }

            // add scores to grader
            foreach ($metrics as $metric) {
                $this->scores[] = $metric->getScoreForGrader();
            }
        }
    }

    protected function gradeSkills()
    {
        $scores = [];
        $count = $this->children->count();

        if ($count) {
            // get children average skills scores
            foreach ($this->children as $child) {
                foreach ($child->skillset->getSkills(keyLabels: false) as $i => $s) {
                    $scores[$i] = ($scores[$i] ?? 0) + $s;
                }
            }

            foreach ($scores as $i => $s) {
                $scores[$i] = round($s / $count, 0);
            }

            // create skillset for the team
            $skillset = $this->team->skillsets()->updateOrCreate([
                'date' => now()->toDateString(),
                'source' => 'grader',
            ], $scores);

            // add scores to grader
            $this->scores[] = $skillset->fresh()->getScoreForGrader();
        }
    }
}
