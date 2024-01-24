<?php

namespace App\Console\Commands;

use App\Jobs\Velocity\SyncEngineers;
use App\Jobs\Velocity\SyncMetrics;
use App\Jobs\Velocity\SyncTeams;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class SyncVelocity extends Command
{
    protected $signature = 'velocity:sync {--R|run=*}';

    protected $description = 'Sync Velocity\'s teams, engineers, and metrics';

    public function handle()
    {
        $runners = $this->option('run');
        $chain = [];

        if (empty($runners)) {
            $runners = ['teams', 'engineers', 'metrics'];
        }

        if (in_array('teams', $runners)) {
            $chain[] = new SyncTeams();
        }

        if (in_array('engineers', $runners)) {
            $chain[] = new SyncEngineers();
        }

        if (in_array('metrics', $runners)) {
            $chain[] = new SyncMetrics();
        }
        
        if (! empty($chain)) {
            Bus::chain($chain)->dispatch();
        }
    }
}
