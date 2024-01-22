<?php

namespace App\Console\Commands;

use App\Jobs\Graders\Workflows\GradeClustersWorkflow;
use App\Models\Team;
use Illuminate\Console\Command;

class GradeClusters extends Command
{
    protected $signature = 'grade:clusters';

    protected $description = 'Run clusters\' (engineers and teams) graders';

    public function handle()
    {
        foreach (Team::isRoot()->get() as $team) {
            GradeClustersWorkflow::start($team);
        }
    }
}
