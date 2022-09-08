<?php

namespace YorCreative\QueryWatcher;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Pusher\Pusher;
use YorCreative\QueryWatcher\Events\QueryEvent;
use YorCreative\QueryWatcher\Listeners\QueryListener;

class QueryWatcherServiceProvider extends ServiceProvider
{
    protected array $listeners = [
        QueryEvent::class => [
            QueryListener::class,
        ],
    ];

    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__, 1) . '/config/querywatcher.php', 'querywatcher');

        $this->loadRoutesFrom(dirname(__DIR__, 1) . '/routes/BroadcastAuthRoute.php');

        $this->publishes([
            dirname(__DIR__, 1) . '/config' => base_path('config'),
        ]);

        QueryWatcher::listen();

        $this->registerEventListeners();
    }

    public function registerEventListeners()
    {
        $listeners = $this->getEventListeners();
        foreach ($listeners as $listenerKey => $listenerValues) {
            foreach ($listenerValues as $listenerValue) {
                Event::listen(
                    $listenerKey,
                    $listenerValue
                );
            }
        }
    }

    public function getEventListeners(): array
    {
        return $this->listeners;
    }

    public function boot()
    {
        require dirname(__DIR__, 1) . '/routes/QueryChannel.php';

        $this->app->singleton(Pusher::class, function () {
            return new Pusher(
                Config::get('broadcasting.connections.pusher.key'),
                Config::get('broadcasting.connections.pusher.secret'),
                Config::get('broadcasting.connections.pusher.app_id')
            );
        });
    }
}
