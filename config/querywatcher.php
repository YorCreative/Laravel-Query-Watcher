<?php

return [
    'enabled' => true,
    'token' => env('QUERY_WATCH_TOKEN', 'change_me'),
    'scope' => [
        'time_exceeds_ms' => [
            'enabled' => false,
            'threshold' => 2,
        ],
        'context' => [
            'auth_user' => [
                'enabled' => true,
                'ttl' => 300,
            ],
            'trigger' => [
                'enabled' => true,
            ],
        ],
        'ignorable_tables' => [
            'jobs',
        ],
        'ignorable_statements' => [
            'create',
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
        'slack' => [
            'enabled' => false,
            'hook' => env('SLACK_HOOK', 'placeholder')
        ]
    ],
];
