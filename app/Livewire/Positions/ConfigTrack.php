<?php

namespace App\Livewire\Positions;

use App\Models\Position;
use Illuminate\Validation\Rule;
use Livewire\Component;
use App\Models\Enums\PositionType;

class ConfigTrack extends Component
{
    public Position $position;

    public $title;

    public $code;

    public $type;

    public $labels;

    public $levels;

    public function mount(Position $position)
    {
        $this->position = $position;

        if ($position->exists) {
            abort_unless($position->isTrack(), 404);
            $this->authorize('edit', $position);
        } else {
            $this->authorize('create', Position::class);
        }

        $this->type = PositionType::Engineer;

        $this->fill($position->only([
            'title', 'code', 'type',
        ]));

        $skills = $position->skills->keyBy('skill');

        for ($i = 0; $i < 10; $i++) {
            $skill = $skills[$i] ?? [];
            $this->labels[$i] = $skill['skill_label'] ?? '';

            for ($j = 0; $j < 6; $j++) {
                $this->levels[$i][$j] = $skill["l{$j}_description"] ?? '';
            }
        }
    }

    public function save()
    {
        $positionData = $this->validate([
            'title' => [
                'required',
                'max:255',
                Rule::unique('positions')
                    ->where(fn ($q) => $q->where('type', 'track'))
                    ->ignore($this->position->id),
            ],
            'code' => [
                'required',
                'max:3',
                Rule::unique('positions')
                    ->where(fn ($q) => $q->where('type', 'track'))
                    ->ignore($this->position->id),
            ],
        ]);

        $skillsData = $this->validate([
            'labels.*' => [
                'required',
                'string',
                'max:12',
            ],
            'levels.*.*' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $positionData['type'] = 'track';
        $positionData['track'] = $positionData['code'];
        $positionData['level'] = 0;

        $this->position->fill($positionData);
        $this->position->save();

        foreach ($skillsData['labels'] as $i => $label) {
            $levels = $skillsData['levels'][$i];

            $this->position->skills()->updateOrCreate([
                'skill' => $i,
            ], [
                'skill_label' => $label,
                'l0_description' => $levels[0],
                'l1_description' => $levels[1],
                'l2_description' => $levels[2],
                'l3_description' => $levels[3],
                'l4_description' => $levels[4],
                'l5_description' => $levels[5],
            ]);
        }

        $this->redirect(route('tracks.show', $this->position));
    }
}
