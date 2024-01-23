<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditTeam extends Component
{
    public Team $team;

    public $name;

    public $parent_id;

    public $is_cluster = false;

    public $parents;

    public function mount(Team $team)
    {
        $this->team = $team;

        if ($team->exists) {
            $this->authorize('edit', $team);
        } else {
            $this->authorize('create', Team::class);
        }

        $this->fill($team->only([
            'name', 'parent_id', 'is_cluster',
        ]));

        $subtree = $team->descendantsAndSelf()->get()->pluck('id')->all();
        $this->parents = Team::whereNotIn('id', $subtree)->get();
    }

    public function update()
    {
        $validated = $this->validateInput();

        $this->team->fill($validated);
        $this->team->save();

        if ($this->team->wasRecentlyCreated) {
            Auth::user()->teams()->attach($this->team);
        }

        $this->redirect(route('teams.show', $this->team));
    }

    protected function validateInput()
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('teams')->ignore($this->team->id),
            ],
            'parent_id' => [
                'nullable',
                'exists:teams,id',
            ],
            'is_cluster' => [
                'nullable',
                'boolean',
            ],
        ];

        return $this->validate($rules);
    }
}
