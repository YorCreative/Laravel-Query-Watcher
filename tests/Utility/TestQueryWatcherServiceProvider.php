<?php

namespace YorCreative\QueryWatcher\Tests\Utility;

use YorCreative\QueryWatcher\QueryWatcherServiceProvider;

class TestQueryWatcherServiceProvider extends QueryWatcherServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
}
