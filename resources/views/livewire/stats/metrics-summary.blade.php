<?php

use App\Models\Metric;
use Livewire\Volt\Component;

new class extends Component
{
    public $metrics;

    public function mount($metricable)
    {
        $this->metrics = $metricable->getWatchedMetrics();
    }

    public function badgeColor($metric)
    {
        return match ($metric->status) {
            'success' => 'bg-green-100 text-green-800',
            'warning' => 'bg-orange-100 text-orange-800',
            'danger' => 'bg-red-100 text-red-800',
        };
    }
}; ?>

<div>
    <dl class="grid grid-cols-1 divide-y divide-gray-200 overflow-hidden bg-white shadow sm:rounded-lg md:grid-cols-4 md:divide-x md:divide-y-0">
        @foreach ($metrics as $metric)
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-base font-normal leading-tight text-gray-900">
                    {{ $metric->label }}
                    @if ($metric->goal == Metric::INCREASE)
                        <x-heroicon-o-arrow-up class="-mt-0.5 inline size-3 self-center" />
                    @else
                        <x-heroicon-o-arrow-down class="-mt-0.5 inline size-3 self-center" />
                    @endif
                </dt>

                <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
                    <div class="flex items-baseline text-2xl font-semibold text-indigo-600">
                        {{ $metric->value ?? 'NULL' }}
                        <span class="ml-1 text-sm font-medium text-gray-500">/ {{ $metric->target }} {{ $metric->unit }}</span>
                    </div>

                    @if ($metric->value)
                        <div class="{{ $this->badgeColor($metric) }} inline-flex items-baseline rounded-full px-2 py-0.5 text-sm font-medium md:mt-2 lg:mt-0">
                            @if ($metric->status == 'success')
                                <x-heroicon-o-check class="-mr-1 h-4 w-4 flex-shrink-0 self-center" />
                                &nbsp;
                            @else
                                <x-heroicon-o-play class="-mr-1 h-4 w-4 flex-shrink-0 -rotate-90 self-center" />
                                &nbsp;
                            @endif

                            @if (! in_array($metric->deviation, [0, INF]))
                                <div class="ml-1">{{ $metric->deviation }}%</div>
                            @endif
                        </div>
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>
</div>
