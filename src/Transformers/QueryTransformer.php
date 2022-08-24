<?php

namespace YorCreative\QueryWatcher\Transformers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Config;

class QueryTransformer
{
    protected $transformed = [];

    /**
     * @param  QueryExecuted  $query
     * @return array
     */
    public static function transform(QueryExecuted $query): array
    {
        $context = [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time,
            'connection' => $query->connectionName,
        ];

        if (Config::get('querywatcher.scope.context.trigger.enabled')) {
            $context = array_merge($context, [
                'trigger' => TriggerTransformer::transform(),
            ]);
        }

        if (Config::get('querywatcher.scope.context.auth_user.enabled')) {
            $context = array_merge($context, [
                'auth' => AuthTransformer::transform(),
            ]);
        }

        return $context;
    }
}
