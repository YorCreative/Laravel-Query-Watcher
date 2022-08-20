<?php

namespace YorCreative\QueryWatcher\Transformers;

class TriggerTransformer
{
    public static function transform()
    {
        $action = app()->runningInConsole() ? 'Console' : 'Request';

        $context = app()->runningInConsole() ? [
            'url' => null,
            'input' => null,
        ] : [
            'url' => request()->url(),
            'method' => request()->getMethod(),
            'input' => request()->all(),
        ];

        return [
            'action' => $action,
            'context' => $context,
        ];
    }
}
