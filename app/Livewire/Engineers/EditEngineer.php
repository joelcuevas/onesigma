<?php

namespace App\Livewire\Engineers;

use App\Jobs\Graders\GradeEngineer;
use App\Models\Engineer;
use App\Models\Position;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditEngineer extends Component
{
    public Engineer $engineer;

    public $name;

    public $email;

    public $track;

    public $tracks;

    public function mount(Engineer $engineer)
    {
        $this->authorize('show', $engineer);

        $this->engineer = $engineer;

        $this->fill(
            $engineer->only('name', 'email', 'track'),
        );

        $this->tracks = Position::where('type', 'engineer')
            ->orderBy('track')
            ->pluck('title', 'track');
    }

    public function update()
    {
        $this->authorize('update', $this->engineer);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'track' => [
                'required',
                Rule::in($this->tracks->keys()),
            ],
        ]);

        $this->engineer->fill($validated);
        $rescore = $this->engineer->isDirty('track');

        $this->engineer->save();

        if ($rescore) {
            $skills = $this->engineer->skillset->only([
                's0', 's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9',
            ]);

            $this->engineer->refresh()->skillsets()->create($skills);

            GradeEngineer::dispatch($this->engineer);
        }

        $this->redirect(route('engineers.show', $this->engineer));
    }
}
