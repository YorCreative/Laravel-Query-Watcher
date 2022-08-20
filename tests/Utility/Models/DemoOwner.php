<?php

namespace YorCreative\QueryWatcher\Tests\Utility\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use YorCreative\QueryWatcher\Tests\Utility\Traits\NonPublishableHasFactory;

class DemoOwner extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable, NonPublishableHasFactory;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string
     */
    protected $table = 'demo_owners';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string[]
     */
    protected $fillable = [
        'email',
        'name',
    ];
}
