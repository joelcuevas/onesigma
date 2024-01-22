<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $id;

    public $labels;

    public $datasets = [];

    public $height;

    public function mount($labels, $values, $expected, $height = 13)
    {
        $this->id = 'chart_'.uniqid();
        $this->height = $height;
        $this->labels = $labels;

        $this->datasets = [
            [
                'label' => 'Actual',
                'data' => $values,
                'borderColor' => '#4e46e5',
                'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
            ], [
                'label' => 'Esperado',
                'data' => $expected,
                'borderColor' => '#aaa',
                'borderDash' => [5, 5],
                'borderWidth' => 2,
                'backgroundColor' => 'transparent',
            ],
        ];
    }
}; ?>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        const {{ $id }} = new Chart(document.getElementById('{{ $id }}'), {
            type: 'radar',
            data: {
                labels: @json($labels),
                datasets: @json($datasets),
            },
            options: {
                aspectRatio: 1.2,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    r: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                        },
                    },
                },
            },
        });
    </script>
@endpush

<div>
    <canvas style="height: {{ $height }}rem" id="{{ $id }}"></canvas>
</div>
