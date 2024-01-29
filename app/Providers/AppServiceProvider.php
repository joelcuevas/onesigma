<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Relation::enforceMorphMap([
            'engineer' => 'App\Models\Engineer',
            'team' => 'App\Models\Team',
            'metric' => 'App\Models\Metric',
            'skillset' => 'App\Models\Skillset',
            'user' => 'App\Models\User',
            'position' => 'App\Models\Position',
            'position_level' => 'App\Models\PositionLevel',
        ]);
    }
}
