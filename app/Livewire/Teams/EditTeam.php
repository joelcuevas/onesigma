<?php

namespace App\Livewire\Teams;

use App\Models\Enums\TeamStatus;
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

    public $status;

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
            'name', 'parent_id', 'is_cluster', 'status',
        ]));

        $subtree = $team->descendantsAndSelf()->get()->pluck('id')->all();

        $this->parents = Auth::user()
            ->getTeams()
            ->reject(fn ($t) => ! $t->isCluster() || in_array($t->id, $subtree))
            ->map(function ($t) {
                $t->name = str_repeat('—', $t->depth).' '.$t->name;

                return $t;
            });
    }

    public function update()
    {
        $validated = $this->validate([
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
            'status' => [
                'required',
                Rule::enum(TeamStatus::class),
            ],
        ]);

        $this->team->fill($validated);

        if ($this->team->isDirty('status')) {
            $sub = $this->team->descendantsAndSelf->pluck('id')->all();
            Team::whereIn('id', $sub)->update(['status' => $validated['status']]);
        }

        $this->team->save();

        if ($this->team->wasRecentlyCreated) {
            Auth::user()->teams()->attach($this->team);
        }

        $this->redirect(route('teams.show', $this->team));
    }
}
