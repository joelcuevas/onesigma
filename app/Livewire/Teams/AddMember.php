<?php

namespace App\Livewire\Teams;

use Livewire\Component;
use App\Models\Engineer;

class AddMember extends Component
{
    public $team;

    public $engineers;

    public $engineerId;

    public $engineer;

    public $engineerName;

    public $role = 'D';

    public function mount($team)
    {
        $this->team = $team;
        $this->fetchEngineers();
    }

    public function updatedEngineerId()
    {
        $this->engineer = $this->engineers
            ->where('id', $this->engineerId)
            ->first();
            
        $this->engineerName = $this->engineer->name;
    }

    protected function fetchEngineers()
    {
        $ids = $this->team->engineers->pluck('id')->all();

        $this->engineers = Engineer::orderBy('name')
            ->whereNotIn('id', $ids)
            ->get();
    }

    public function filter()
    {
        $ids = $this->team->engineers->pluck('id')->all();

        $this->engineers = Engineer::orderBy('name')
            ->where('name', 'like', '%'.$this->engineerName.'%')
            ->whereNotIn('id', $ids)
            ->get();
    }

    public function add()
    {
        $this->team->engineers()->attach($this->engineer, ['role' => $this->role]);
        $this->team->refresh();
        $this->fetchEngineers();

        $this->dispatch('close-modal')->self();
        $this->dispatch('engineers-updated')->to(TeamMembersTable::class); 

        $this->reset('engineerId');
        $this->reset('engineerName');
        $this->reset('role');
    }
}
