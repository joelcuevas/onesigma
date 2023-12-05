<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Livewire\Engineers\ListEngineers;
use App\Livewire\Engineers\ShowEngineer;
use App\Livewire\Teams\ListTeams;
use App\Livewire\Teams\ShowTeam;

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

Route::get('/', function () {
    if (request()->user()) {
        return redirect('/dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function() {
    return redirect('/teams');
})->name('dashboard');

Route::controller(EngineersController::class)->group(function() {
    Route::get('/login', 'login')->name('login');
    Route::get('/auth/callback', 'callback');
    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::controller(EngineersController::class)->group(function() {
        Route::get('/engineers', 'index')->name('engineers');
    });

    Route::get('/engineers', ListEngineers::class)->name('engineers');
    Route::get('engineers/{engineer}', ShowEngineer::class)->name('engineers.show');

    Route::get('/teams', ListTeams::class)->name('teams');
    Route::get('/teams/{team}', ShowTeam::class)->name('teams.show');
});
