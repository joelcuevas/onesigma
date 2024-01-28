<?php

namespace Tests\Feature;

use App\Models\Engineer;
use App\Models\Metric;
use App\Models\MetricConfig;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\ConfigsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_metric_configs_can_be_created()
    {
        MetricConfig::create([
            'metric' => 'test',
            'label' => 'Testing',
            'target' => 99,
            'goal' => 1,
            'unit' => 'TC',
        ]);

        $mc = MetricConfig::where('metric', 'test')->first();

        $this->assertEquals('Testing', $mc->label);
    }

    public function test_latest_metrics_can_be_retrieved()
    {
        config(['onesigma.metrics.velocity' => ['a', 'b', 'c']]);

        $engineer = Engineer::factory()->addMetrics(3)->create();
        $this->assertEquals(9, $engineer->metrics->count());

        $latest = $engineer->getLatestMetrics();
        $this->assertEquals(3, $latest->count());
        $this->assertTrue($latest->shift()->metricable->is($engineer));
        $this->assertTrue($latest->shift()->metricable->is($engineer));
        $this->assertTrue($latest->shift()->metricable->is($engineer));
    }

    public function test_metric_deviation_is_computed()
    {
        MetricConfig::factory()->set('increase-below', 4, 1)->create();
        MetricConfig::factory()->set('increase-above', 3, 1)->create();
        MetricConfig::factory()->set('decrease-below', 1, -1)->create();
        MetricConfig::factory()->set('decrease-above', 0.5, -1)->create();
        MetricConfig::factory()->set('inf-increase-below', 0, 1)->create();
        MetricConfig::factory()->set('inf-increase-above', 0, 1)->create();
        MetricConfig::factory()->set('inf-decrease-below', 0, -1)->create();
        MetricConfig::factory()->set('inf-decrease-above', 0, -1)->create();

        $engineer = Engineer::factory()
            ->has(Metric::factory()->set('increase-below', 2))
            ->has(Metric::factory()->set('increase-above', 4))
            ->has(Metric::factory()->set('decrease-below', 1.5))
            ->has(Metric::factory()->set('decrease-above', 0.2))
            ->has(Metric::factory()->set('inf-increase-below', -0.5))
            ->has(Metric::factory()->set('inf-increase-above', 0.7))
            ->has(Metric::factory()->set('inf-decrease-below', 1.5))
            ->has(Metric::factory()->set('inf-decrease-above', -0.2))
            ->create();

        $latest = $engineer->getLatestMetrics();

        $this->assertEquals(50, $latest['increase-below']->progress);
        $this->assertEquals(50, $latest['increase-below']->deviation);

        $this->assertEquals(100, $latest['increase-above']->progress);
        $this->assertEquals(0, $latest['increase-above']->deviation);

        $this->assertEquals(150, $latest['decrease-below']->progress);
        $this->assertEquals(50, $latest['decrease-below']->deviation);

        $this->assertEquals(100, $latest['decrease-above']->progress);
        $this->assertEquals(0, $latest['decrease-above']->deviation);

        $this->assertEquals(INF, $latest['inf-increase-below']->progress);
        $this->assertEquals(INF, $latest['inf-increase-below']->deviation);

        $this->assertEquals(100, $latest['inf-increase-above']->progress);
        $this->assertEquals(0, $latest['inf-increase-above']->deviation);

        $this->assertEquals(INF, $latest['inf-decrease-below']->progress);
        $this->assertEquals(INF, $latest['inf-decrease-below']->deviation);

        $this->assertEquals(100, $latest['inf-decrease-above']->progress);
        $this->assertEquals(0, $latest['inf-decrease-above']->deviation);
    }

    public function test_watched_metrics_are_rendered_in_team_details()
    {
        $this->seed(ConfigsSeeder::class);

        MetricConfig::factory()->set('metric-1', 4, 1)->create();
        MetricConfig::factory()->set('metric-2', 3, 1)->create();
        MetricConfig::factory()->set('metric-3', 1, -1)->create();

        config(['onesigma.metrics.watching' => ['metric-1', 'metric-2', 'metric-3']]);

        $team = Team::factory()
            ->hasSkillset()
            ->hasEngineers(5)
            ->create();

        $user = User::factory()->admin()->create();
        $user->teams()->attach($team);

        $this->actingAs($user);

        $this->get(route('teams.show', $team))
            ->assertStatus(200)
            ->assertSee('metric-1')
            ->assertSee('metric-2')
            ->assertSee('metric-3');
    }

    public function test_watched_metrics_are_rendered_in_engineer_profile()
    {
        $this->seed(ConfigsSeeder::class);

        MetricConfig::factory()->set('perf1', 4, 1)->create();
        MetricConfig::factory()->set('perf2', 3, 1)->create();
        MetricConfig::factory()->set('perf3', 1, -1)->create();

        config(['onesigma.metrics.watching' => ['perf1', 'perf2', 'perf3']]);

        $team = Team::factory()
            ->hasSkillset()
            ->has(Engineer::factory()
                ->count(1)
                ->addMetrics(3, ['perf1', 'perf2', 'perf3'])
                ->hasSkillset()
            )
            ->create();

        $user = User::factory()->admin()->create();
        $user->teams()->attach($team);

        $engineer = $team->engineers->first();
        $m = $engineer->getWatchedMetrics();

        $this->actingAs($user);

        $this->get(route('engineers.show', $engineer))
            ->assertStatus(200)
            ->assertSeeTextInOrder([
                $m['perf1']->label,
                $m['perf1']->value,
                $m['perf1']->target,
            ])
            ->assertSeeTextInOrder([
                $m['perf2']->label,
                $m['perf2']->value,
                $m['perf2']->target,
            ])
            ->assertSeeTextInOrder([
                $m['perf3']->label,
                $m['perf3']->value,
                $m['perf3']->target,
            ]);
    }

    public function test_scores_are_converted_to_grades()
    {
        $engineer = Engineer::factory()->create();

        $engineer->setGrade([0]);
        $this->assertEquals('A+', $engineer->fresh()->grade);

        $engineer->setGrade([-1]);
        $this->assertEquals('A', $engineer->fresh()->grade);

        $engineer->setGrade([-2]);
        $this->assertEquals('B', $engineer->fresh()->grade);

        $engineer->setGrade([-7]);
        $this->assertEquals('D', $engineer->fresh()->grade);

        $engineer->setGrade([-25]);
        $this->assertEquals('F', $engineer->fresh()->grade);
    }
}
