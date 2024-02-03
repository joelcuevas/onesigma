<?php

namespace App\Console\Commands;

use App\Metrics\MetricsWorkflow;
use Illuminate\Console\Command;

class GenerateMetrics extends Command
{
    protected $signature = 'metrics:generate';

    protected $description = 'Ingest, aggregate and generate metrics';

    public function handle()
    {
        MetricsWorkflow::start();
    }
}
