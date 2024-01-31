<?php

use App\Livewire\Engineers\EditEngineer;
use App\Livewire\Engineers\ScoreEngineer;
use App\Livewire\Engineers\ShowEngineer;
use App\Livewire\Positions\ConfigTrack;
use App\Livewire\Positions\IndexTracks;
use App\Livewire\Positions\ShowTrack;
use App\Livewire\Teams\EditTeam;
use App\Livewire\Teams\IndexTeams;
use App\Livewire\Teams\ShowTeam;
use App\Livewire\Users\EditUser;
use App\Livewire\Users\IndexUsers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn () => redirect('login'));

Route::middleware('auth')->group(function () {
    Route::get('teams', IndexTeams::class)
        ->name('teams')
        ->middleware('can:index,App\Models\Team');

    Route::get('teams/create', EditTeam::class)
        ->name('teams.create')
        ->middleware('can:create,App\Models\Team');

    Route::get('teams/{team}/edit', EditTeam::class)
        ->name('teams.edit')
        ->middleware('can:edit,team');

    Route::get('teams/{team}', ShowTeam::class)
        ->name('teams.show')
        ->middleware('can:show,team');

    // engineers

    Route::get('engineers/{engineer}/edit', EditEngineer::class)
        ->name('engineers.edit')
        ->middleware('can:update,engineer');

    Route::get('engineers/{engineer}/score', ScoreEngineer::class)
        ->name('engineers.score');

    Route::get('engineers/{engineer}', ShowEngineer::class)
        ->name('engineers.show')
        ->middleware('can:show,engineer');

    // users

    Route::get('users', IndexUsers::class)
        ->name('users')
        ->middleware('can:index,App\Models\User');

    Route::get('users/create', EditUser::class)
        ->name('users.create')
        ->middleware('can:create,App\Models\User');

    Route::get('users/{user}/edit', EditUser::class)
        ->name('users.edit')
        ->middleware('can:edit,user');

    // positions

    Route::get('tracks', IndexTracks::class)
        ->name('tracks')
        ->middleware('can:index,App\Models\Position');

    Route::get('tracks/{position}', ShowTrack::class)
        ->name('tracks.show')
        ->middleware('can:show,position');

    Route::get('tracks/{position}/config', ConfigTrack::class)
        ->name('tracks.config')
        ->middleware('can:edit,position');
});

Route::get('dashboard', fn () => redirect('/teams'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
