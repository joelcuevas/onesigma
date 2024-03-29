<?php

namespace App\Metrics\Aggregators\Teams;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Sassnowski\Venture\WorkflowableJob;
use Sassnowski\Venture\WorkflowStep;

class AverageTeam implements WorkflowableJob
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

        $this->averageMetrics();
        $this->averageSkillsets();
    }

    protected function averageMetrics()
    {
        $averages = [];
        $counts = [];

        if ($this->children->count()) {
            // average children metrics
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
                $value = bcdiv($v, $counts[$m], 2);

                $metrics[] = $this->team->metrics()->updateOrCreate([
                    'metric' => $m,
                    'date' => now()->toDateString(),
                ], [
                    'value' => $value,
                    'source' => 'computed',
                ]);
            }
        }
    }

    protected function averageSkillsets()
    {
        $scores = [];
        $count = $this->children->count();

        if ($count) {
            // average children skillsets
            foreach ($this->children as $child) {
                $skills = $child->skillset->onlySkills();

                foreach ($skills as $i => $s) {
                    $scores[$i] = ($scores[$i] ?? 0) + $s;
                }
            }

            foreach ($scores as $i => $s) {
                $scores[$i] = bcdiv($s, $count, 2);
            }

            // create skillset for the team
            $skillset = $this->team->skillsets()->updateOrCreate([
                'date' => now()->toDateString(),
                'source' => 'grader',
            ], $scores);
        }
    }
}
