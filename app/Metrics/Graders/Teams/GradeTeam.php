<?php

namespace App\Metrics\Graders\Teams;

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

        $count = count($this->children);
        $score = null;

        if ($count) {
            $scores = 0;

            foreach ($this->children as $children) {
                $scores += $children->score;
            }

            $score = [bcdiv($scores, $count, 0)];
        }

        $this->team->updateGrade($score);
    }
}
