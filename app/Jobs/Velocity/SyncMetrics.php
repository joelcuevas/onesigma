<?php

namespace App\Jobs\Velocity;

use App\Models\Engineer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SyncMetrics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $baseUrl;

    protected $bearerToken;

    protected $metrics;

    protected $date;

    protected $range = 'last7days';

    public function __construct()
    {
        $this->baseUrl = 'https://velocity.codeclimate.com/api/metric';
        $this->bearerToken = config('services.velocity.token');
        $this->metrics = config('onesigma.metrics.watching');
        $this->date = now()->toDateString();
    }

    public function handle(): void
    {
        foreach ($this->metrics as $metric) {
            $this->fetchMetric($metric);
        }
    }

    public function fetchMetric($metricName)
    {
        $url = $this->baseUrl.'?group[]=owner&date_range='.$this->range.'&name='.$metricName;
        $response = Http::withToken($this->bearerToken)->timeout(15)->get($url);

        foreach ($response['data'] as $metric) {
            $ownerId = $metric['group']['owner_id'];
            $engineer = Engineer::whereIdentity('velocity', $ownerId)->first();

            if ($engineer) {
                $value = $metric['values'][0]['value'] ?? 0;
                $drilldown = $metric['values'][0]['links']['drilldown'];

                $engineer->metrics()->updateOrCreate([
                    'date' => $this->date,
                    'source' => 'velocity',
                    'metric' => $metricName,
                ], [
                    'value' => $value,
                    'context' => ['drilldown' => $drilldown],
                ]);
            }
        }
    }
}
