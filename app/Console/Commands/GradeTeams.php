<?php

namespace App\Console\Commands;

use App\Jobs\Graders\Workflows\GradeTeamWorkflow;
use App\Models\Team;
use Illuminate\Console\Command;

class GradeTeams extends Command
{
    protected $signature = 'grade:teams';

    protected $description = 'Run teams and engineers graders';

    public function handle()
    {
        foreach (Team::isRoot()->get() as $team) {
            GradeTeamWorkflow::start($team);
        }
    }
}
