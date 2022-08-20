<?php

namespace YorCreative\QueryWatcher\Tests\Utility;

use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TestBroadcasterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TestBroadcast::class, function () {
            return new TestBroadcast();
        });
    }

    /**
     * Bootstrap services.
     *
     * @param  BroadcastManager  $broadcastManager
     */
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('test', function (Application $app, array $config) {
            return $app->make(TestBroadcast::class);
        });
    }
}
