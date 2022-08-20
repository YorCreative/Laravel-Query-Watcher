<?php

namespace YorCreative\QueryWatcher\Models;

use Illuminate\Database\Eloquent\Model;

class QueryModel extends Model
{
    protected $fillable = [
        'sql',
        'bindings',
        'time',
        'connection',
        'trigger',
        'auth',
    ];

    protected $casts = [
        'sql' => 'string',
        'bindings' => 'array',
        'time' => 'float',
        'connection' => 'string',
        'trigger' => 'array',
        'auth' => 'array',
    ];

    public function getBindings()
    {
        return $this->bindings;
    }
}
