<?php

namespace App\Console\Commands;

use App\Jobs\Velocity\SyncEngineers;
use App\Jobs\Velocity\SyncMetrics;
use App\Jobs\Velocity\SyncTeams;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class SyncVelocity extends Command
{
    protected $signature = 'velocity:sync';

    protected $description = 'Sync Velocity\'s engineers and metrics';

    public function handle()
    {
        Bus::chain([
            new SyncTeams(),
            new SyncEngineers(),
            new SyncMetrics(),
        ])->dispatch();
    }
}
