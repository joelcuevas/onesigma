<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $labels;

    public $values;

    public $expected;

    public function mount($skillable)
    {
        $skills = collect($skillable->getCurrentSkills())->chunk(5);
        $expected = collect($skillable->getPositionSkills())->chunk(5);

        $careerSkills = $skills[0];
        $careerExpected = $expected[0];

        $domainSkills = $skills[1];
        $domainExpected = $expected[1];

        $this->labels[0] = array_keys($careerSkills->all());
        $this->values[0] = array_values($careerSkills->all());
        $this->expected[0] = array_values($careerExpected->all());

        $this->labels[1] = array_keys($domainSkills->all());
        $this->values[1] = array_values($domainSkills->all());
        $this->expected[1] = array_values($domainExpected->all());
    }
}; ?>

<div class="flex flex-col space-y-6">
    <div class="grid grid-cols-1 divide-y divide-gray-200 overflow-hidden bg-white shadow sm:rounded-lg md:grid-cols-2 md:divide-x md:divide-y-0">
        <div class="flex flex-col items-center justify-center py-2">
            <h3 class="font-xl mb-3 mt-5">Capacidades</h3>
            <livewire:stats.skills-chart :labels="$labels[0]" :values="$values[0]" :expected="$expected[0]" />
        </div>

        <div class="flex flex-col items-center justify-center py-2">
            <h3 class="font-xl mb-3 mt-5">Competencias</h3>
            <livewire:stats.skills-chart :labels="$labels[1]" :values="$values[1]" :expected="$expected[1]" />
        </div>
    </div>
</div>
