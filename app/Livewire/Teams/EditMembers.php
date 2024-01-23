<?php

namespace App\Livewire\Teams;

use App\Jobs\Graders\GradeTeam;
use App\Models\Engineer;
use App\Models\Enums\TeamEngineerRole;
use App\Models\Enums\TeamUserRole;
use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class EditMembers extends Component
{
    public Team $team;

    public $relationship;

    public $name;

    public $allRoles;

    public $defaultRole;

    public $members;

    public $memberRoles;

    public $orphans;

    public $orphanId = '';

    public function mount(Team $team, $relationship, $name)
    {
        $this->authorize('edit-members', $team);

        $this->team = $team;
        $this->relationship = $relationship;
        $this->name = $name;

        $this->load();
    }

    #[On('close-modal')]
    public function load()
    {
        $this->members = $this->team->{$this->relationship};

        foreach ($this->members as $m) {
            $this->memberRoles[$m->id] = $m->team->role;
        }

        if ($this->relationship == 'engineers') {
            $this->allRoles = TeamEngineerRole::cases();
            $this->defaultRole = TeamEngineerRole::Engineer;
            $this->orphans = Engineer::doesntHave('teams')->get();
        } else {
            $this->allRoles = TeamUserRole::cases();
            $this->defaultRole = TeamUserRole::Manager;

            $this->orphans = User::where(function ($q) {
                $q->whereNotIn('id', $this->members->pluck('id'));
            })->get();
        }

        $this->orphanId = '';
    }

    public function add()
    {
        $member = $this->orphans->firstWhere('id', $this->orphanId);
        $this->orphans = $this->orphans->filter(fn ($o) => $o->id != $member->id);
        $this->orphanId = '';
        $this->memberRoles[$member->id] = $this->defaultRole;
        $this->members->push($member);
    }

    public function remove($id)
    {
        $member = $this->members->firstWhere('id', $id);
        $this->members = $this->members->filter(fn ($t) => $t->id != $id);
        $this->orphans->push($member);
    }

    public function save()
    {
        $members = $this->members->mapWithKeys(function ($m, $k) {
            return [$m->id => ['role' => $this->memberRoles[$m->id]->value]];
        });

        $this->team->{$this->relationship}()->sync($members->all());

        $this->dispatch('close-modal', $this->name);
        $this->dispatch('team-updated');
        $this->dispatch('team-members-updated');

        GradeTeam::dispatch($this->team);
    }
}
