<?php

return [
    'enabled' => true,
    'token' => env('QUERY_WATCH_TOKEN', 'change_me'),
    'scope' => [
        'time_exceeds_ms' => [
            'enabled' => false,
            'threshold' => 2,
        ],
    ],
    'listener' => [
        'connection' => 'sync',
        'queue' => 'default',
        'delay' => null,
    ],
    'channels' => [
        'discord' => [
            'enabled' => false,
            'hook' => env('DISCORD_HOOK', 'placeholder'),
        ],
    ],
];
