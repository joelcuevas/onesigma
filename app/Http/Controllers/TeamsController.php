<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamsController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::orderBy('name')->get();

        return view('teams.index', ['teams' => $teams]);
    }

    public function show(Team $team, Request $request)
    {
        return view('teams.show', ['team' => $team, 'engineers' => $team->engineers]);
    }
}
