<?php

return [

    'metrics' => [
        'watching' => [
            'average_weekly_coding_days',
            'innovation_rate',
            'time_to_review',
        ],

        'velocity' => [
            'commits_per_day',
            'innovation_rate',
            'time_to_review',
            'average_weekly_coding_days',
        ],
    ],

    'velocity' => [
        'cluster_id' => env('VELOCITY_CLUSTER_ID'),
    ],

];
