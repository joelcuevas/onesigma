<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Support\Velocity\EngineersSynchronizer;

class SyncVelocityEngineers extends Command
{
    protected $signature = 'velocity:engineers';

    protected $description = 'Sync Velocity engineers and teams';

    public function handle()
    {
        EngineersSynchronizer::sync();
    }
}
