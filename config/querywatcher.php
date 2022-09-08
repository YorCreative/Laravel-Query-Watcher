<?php

return [
    'enabled' => env('QUERY_WATCH_ENABLED', true),
    'token' => env('QUERY_WATCH_TOKEN', 'change_me'),
    'scope' => [
        'time_exceeds_ms' => [
            'enabled' => env('QUERY_WATCH_SCOPE_TIME_ENABLED', false),
            'threshold' => env('QUERY_WATCH_SCOPE_TIME_THRESHOLD', 500),
        ],
        'context' => [
            'auth_user' => [
                'enabled' => env('QUERY_WATCH_SCOPE_CONTEXT_AUTH_ENABLED', true),
                'ttl' => env('QUERY_WATCH_SCOPE_CONTEXT_AUTH_TTL', 300),
            ],
            'trigger' => [
                'enabled' => env('QUERY_WATCH_SCOPE_TRIGGER_ENABLED', true),
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
            'enabled' => env('QUERY_WATCH_CHANNEL_DISCORD_ENABLED', false),
            'hook' => env('DISCORD_HOOK', 'placeholder'),
        ],
        'slack' => [
            'enabled' => env('QUERY_WATCH_CHANNEL_SLACK_ENABLED', false),
            'hook' => env('SLACK_HOOK', 'placeholder'),
        ],
    ],
];
