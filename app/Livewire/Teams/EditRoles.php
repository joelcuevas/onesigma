<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Team;

class EditRoles extends Component
{
    public Team $team;

    public $roles = [];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->loadRoles();
    }

    public function save()
    {
        foreach ($this->roles as $id => $role) {
            $this->team->members()
                ->updateExistingPivot(
                    $id,
                    ['role' => $role]
                );
        }    

        $this->dispatch('close-modal');
        $this->dispatch('team-updated');
    }

    public function resetModal()
    {
        $this->resetExcept('team');
        $this->loadRoles();
    }

    protected function loadRoles()
    {
        foreach ($this->team->members as $m) {
            $this->roles[$m->id] = $m->pivot->role;
        }
    }
}
