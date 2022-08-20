<?php

namespace YorCreative\QueryWatcher\Transformers;

use Illuminate\Database\Events\QueryExecuted;

class QueryTransformer
{
    protected $transformed = [];

    /**
     * @param  QueryExecuted  $query
     * @return array
     */
    public static function transform(QueryExecuted $query): array
    {
        return [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time,
            'connection' => $query->connectionName,
            'trigger' => TriggerTransformer::transform(),
            'auth' => AuthTransformer::transform(),
        ];
    }
}
