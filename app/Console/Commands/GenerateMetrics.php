<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Metrics\Ingestors\IngestorsWorkflow;

class GenerateMetrics extends Command
{
    protected $signature = 'metrics:generate';

    protected $description = 'Ingest, aggregate and generate metrics';

    public function handle()
    {
        IngestorsWorkflow::start();
    }
}
