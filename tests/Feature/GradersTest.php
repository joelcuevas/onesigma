<?php

namespace Tests\Feature;

use App\Jobs\Graders\GradeEngineer;
use App\Jobs\Graders\GradeTeam;
use App\Jobs\Graders\Workflows\GradeTeamWorkflow;
use App\Models\Engineer;
use App\Models\MetricConfig;
use App\Models\Team;
use Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradersTest extends TestCase
{
    use RefreshDatabase;

    public function test_engineer_grades_are_computed(): void
    {
        MetricConfig::factory()->set('commits', 2, 1)->create();
        MetricConfig::factory()->set('work', 2, 1)->create();
        MetricConfig::factory()->set('merges', 1, -1)->create();

        $engineer = Engineer::factory()
            ->hasSkillsets(1)
            ->addMetrics()
            ->create();

        GradeEngineer::dispatch($engineer);

        $this->assertNotNull($engineer->fresh()->score);
        $this->assertNotNull($engineer->fresh()->grade);
        $this->assertNotNull($engineer->fresh()->graded_at);
    }

    public function test_team_grades_are_computed(): void
    {
        MetricConfig::factory()->set('commits', 2, 1)->create();
        MetricConfig::factory()->set('work', 2, 1)->create();
        MetricConfig::factory()->set('merges', 1, -1)->create();

        $team = Team::factory()
            ->has(Engineer::factory(3)->addMetrics(3))
            ->create();

        $team->engineers[0]->updateGrade(-1);
        $team->engineers[1]->updateGrade(-3);
        $team->engineers[2]->updateGrade(-5);

        GradeTeam::dispatch($team);

        $this->assertEquals(-3, $team->fresh()->score);
        $this->assertEquals('B', $team->fresh()->grade);
        $this->assertNotNull($team->fresh()->graded_at);
    }

    public function test_grader_workflows_with_team_at_root_runs()
    {
        MetricConfig::factory()->set('commits', 2, 1)->create();
        MetricConfig::factory()->set('work', 2, 1)->create();
        MetricConfig::factory()->set('merges', 1, -1)->create();

        $engineer = Engineer::factory()
            ->hasSkillsets(1)
            ->addMetrics(3)
            ->create();

        $team = Team::factory()
            ->hasAttached($engineer)
            ->create();

        GradeTeamWorkflow::start($team);

        $this->assertNotNull($engineer->fresh()->score);
        $this->assertNotNull($engineer->fresh()->grade);
        $this->assertNotNull($engineer->fresh()->graded_at);

        $this->assertNotNull($team->fresh()->score);
        $this->assertNotNull($team->fresh()->grade);
        $this->assertNotNull($team->fresh()->graded_at);
    }

    public function test_grader_workflows_with_cluster_at_root_runs()
    {
        MetricConfig::factory()->set('commits', 2, 1)->create();
        MetricConfig::factory()->set('work', 2, 1)->create();
        MetricConfig::factory()->set('merges', 1, -1)->create();

        $engineer = Engineer::factory()
            ->hasSkillsets(1)
            ->addMetrics(3)
            ->create();

        $rootCluster = Team::factory()->cluster()->create();
        $nestedCluster = Team::factory()->cluster()->create();
        $nestedCluster->parent()->associate($rootCluster)->save();

        $team = Team::factory()
            ->hasAttached($engineer)
            ->create([
                'parent_id' => $nestedCluster->id,
            ]);

        Artisan::call('grade:teams');

        $this->assertNotNull($engineer->fresh()->graded_at);
        $this->assertNotNull($team->fresh()->graded_at);
        $this->assertNotNull($rootCluster->fresh()->graded_at);
        $this->assertNotNull($nestedCluster->fresh()->graded_at);
    }

    public function test_engineer_grades_are_computed_on_skillset_creation(): void
    {
        MetricConfig::factory()->set('commits', 2, 1)->create();
        MetricConfig::factory()->set('work', 2, 1)->create();
        MetricConfig::factory()->set('merges', 1, -1)->create();

        $engineer = Engineer::factory()
            ->hasSkillsets(1)
            ->addMetrics()
            ->create();

        GradeEngineer::dispatch($engineer);

        $this->assertNotNull($engineer->fresh()->score);
        $this->assertNotNull($engineer->fresh()->grade);
        $this->assertNotNull($engineer->fresh()->graded_at);
    }
}
