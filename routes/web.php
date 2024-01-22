<?php

use App\Livewire\Engineers\EditEngineer;
use App\Livewire\Engineers\ScoreEngineer;
use App\Livewire\Engineers\ShowEngineer;
use App\Livewire\Teams\IndexTeams;
use App\Livewire\Teams\ShowTeam;
use App\Livewire\Teams\EditTeam;
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

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('teams', IndexTeams::class)
        ->name('teams')
        ->middleware('can:index,App\Models\Team');

    Route::get('teams/{team}', ShowTeam::class)
        ->name('teams.show')
        ->middleware('can:show,team');

    Route::get('teams/{team}/edit', EditTeam::class)
        ->name('teams.edit')
        ->middleware('can:edit,team');

    // engineers

    Route::get('engineers/{engineer}', ShowEngineer::class)
        ->name('engineers.show')
        ->middleware('can:show,engineer');

    Route::get('engineers/{engineer}/edit', EditEngineer::class)
        ->name('engineers.edit')
        ->middleware('can:update,engineer');

    Route::get('engineers/{engineer}/score', ScoreEngineer::class)
        ->name('engineers.score');

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
});

Route::get('dashboard', fn () => redirect('/teams'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
