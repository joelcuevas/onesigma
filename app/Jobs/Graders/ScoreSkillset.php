<?php

namespace App\Jobs\Graders;

use App\Models\Position;
use App\Models\Skillset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScoreSkillset implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Skillset $skillset;

    public function __construct(Skillset $skillset)
    {
        $this->skillset = $skillset;
    }

    public function handle(): void
    {
        $track = $this->skillset->position->track;
        $positions = Position::where('track', $track)->get();
        $scores = $this->skillset->getCurrentSkills();
        $diffs = [];

        // find the closest skillset
        foreach ($positions as $p) {
            $diffs[$p->level] = $this->diff($p->getExpectedSkills(), $scores);
        }

        asort($diffs);
        $score = array_key_first($diffs);

        $this->skillset->score = $score;
        $this->skillset->save();
    }

    protected function diff($position, $scores)
    {
        $diffs = [];

        foreach ($position as $level => $expected) {
            $score = array_shift($scores);
            $diffs[$level] = abs($expected - $score);
        }

        return array_sum($diffs);
    }
}
