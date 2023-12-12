<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facades\App\Support\Velocity\MetricsSynchronizer;

class SyncVelocityMetrics extends Command
{
    protected $signature = 'velocity:metrics';

    protected $description = 'Sync Velocity metrics';

    public function handle()
    {
        MetricsSynchronizer::sync();
    }
}
