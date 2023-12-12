<?php

namespace App\Support\Velocity;

use Illuminate\Support\Facades\Http;
use App\Models\Engineer;
use App\Models\Team;
use App\Models\Metric;

class MetricsSynchronizer
{
    protected $engineers;

    protected $baseUrl;

    protected $bearerToken; 

    protected $period;

    public function __construct()
    {
        $this->engineers = Engineer::whereNotNull('velocity_id')->get();
        $this->teams = Team::whereNotNull('velocity_id')->get();

        $this->baseUrl = 'https://velocity.codeclimate.com/api/metric';
        $this->bearerToken = config('services.velocity.token');

        $this->period = now(); 
    }

    public function sync()
    {
        $this->fetchEngineersWeeklyCodingDays();
        $this->fetchTeamsWeeklyCodingDays();

        Metric::where('latest', true)
            ->where('period', '<>', $this->period)
            ->update(['latest' => false]);
    }

    protected function fetchEngineersWeeklyCodingDays()
    {
        $url = $this->baseUrl.'?group[]=owner&name=average_weekly_coding_days&date_range=last7days';

        $response = Http::withToken($this->bearerToken)->timeout(15)->get($url);

        foreach ($response['data'] as $metric) {
            $ownerId = $metric['group']['owner_id'];
            $value = $metric['values'][0]['value'];
            $drilldown = $metric['values'][0]['links']['drilldown'];

            $engineer = $this->engineers->where('velocity_id', $ownerId)->first();

            if ($engineer) {
                $engineer->metrics()->updateOrCreate([
                    'period' => $this->period,
                    'metric' => 'wcd',
                ], [
                    'value' => $value,
                    'latest' => true,
                    'context' => ['drilldown' => $drilldown],
                ]);
            }
        }
    }

    protected function fetchTeamsWeeklyCodingDays()
    {
        $url = $this->baseUrl.'?group[]=owner_team&name=average_weekly_coding_days&date_range=last7days';

        $response = Http::withToken($this->bearerToken)->timeout(15)->get($url);

        foreach ($response['data'] as $metric) {
            $teamId = $metric['group']['owner_team_id'];
            $value = $metric['values'][0]['value'];
            $drilldown = $metric['values'][0]['links']['drilldown'];

            $team = $this->teams->where('velocity_id', $teamId)->first();

            if ($team) {
                $team->metrics()->updateOrCreate([
                    'period' => $this->period,
                    'metric' => 'wcd',
                ], [
                    'value' => $value,
                    'latest' => true,
                    'context' => ['drilldown' => $drilldown],
                ]);
            }
        }
    }
}