<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;
use Illuminate\Validation\Rule;

class EditTeam extends Component
{
    public Team $team;

    public $name;

    public $parent_id;

    public $teams;

    public function mount(Team $team)
    {
        $this->team = $team;

        $this->fill($team->only([
            'name', 'parent_id',
        ]));

        $subtree = $team->descendantsAndSelf()->get()->pluck('id')->all();
        $this->teams = Team::whereNotIn('id', $subtree)->get();
    }

    public function update()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('teams')->ignore($this->team->id),
            ],
            'parent_id' => ['required', 'exists:teams,id']
        ]);

        $this->team->update($validated);

        $this->redirect(route('teams.show', $this->team));
    }
}
