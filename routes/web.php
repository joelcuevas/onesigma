<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EngineersController;
use App\Http\Controllers\TeamsController;

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/auth/callback', [AuthController::class, 'callback']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::controller(EngineersController::class)->group(function() {
        Route::get('/engineers', 'index')->name('engineers');
    });

    Route::controller(TeamsController::class)->group(function() {
        Route::get('/teams', 'index')->name('teams');
        Route::get('/teams/create', 'create')->name('teams.create');
        Route::get('/teams/{team}', 'show')->name('teams.show');
    });
});