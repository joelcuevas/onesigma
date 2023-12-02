<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Engineer;
use App\Models\Team;


class EngineersController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->name;
        $teamId = $request->team_id;

        $engineers = Engineer::orderBy('name')
            ->when($name, function($q, $name) { 
                $q->where('name', 'ilike', '%'.$name.'%');
            })
            ->when($teamId, function($q, $teamId) {
                $q->join('teamables', function($join) {
                        $join
                            ->on('engineers.id', '=', 'teamables.teamable_id')
                            ->where('teamables.teamable_type', '=', 'engineer');
                    })
                    ->whereIn('teamables.team_id', [$teamId]);
            })
            ->get();

        $teams = Team::orderBy('name')->get();

        return view('engineers.index', [
            'engineers' => $engineers,
            'teams' => $teams,
        ]);
    }
}
