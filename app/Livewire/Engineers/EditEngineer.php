<?php

namespace App\Livewire\Engineers;

use App\Metrics\Graders\Engineers\GradeEngineer;
use App\Models\Engineer;
use App\Models\Position;
use Livewire\Component;

class EditEngineer extends Component
{
    public Engineer $engineer;

    public $name;

    public $email;

    public $position_id;

    public $positions;

    public function mount(Engineer $engineer)
    {
        $this->authorize('show', $engineer);

        $this->engineer = $engineer;

        $this->positions = Position::type('engineer')
            ->orderBy('title')
            ->pluck('title', 'id')
            ->all();

        $this->position_id = array_keys($this->positions)[0] ?? null;

        $this->fill(
            $engineer->only('name', 'email', 'position_id'),
        );
    }

    public function update()
    {
        $this->authorize('update', $this->engineer);

        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'position_id' => [
                'required',
                'exists:positions,id',
            ],
        ]);

        $this->engineer->fill($validated);
        $rescore = $this->engineer->isDirty('position_id');

        $this->engineer->save();

        if ($rescore) {
            $skills = $this->engineer->skillset->onlySkills();
            $this->engineer->refresh()->skillsets()->create($skills);

            GradeEngineer::dispatch($this->engineer);
        }

        $this->redirect(route('engineers.show', $this->engineer));
    }
}
