<?php

namespace YorCreative\QueryWatcher;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
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
        $this->mergeConfigFrom(dirname(__DIR__, 1).'/config/querywatcher.php', 'querywatcher');

        $this->publishes([
            dirname(__DIR__, 1).'/config' => base_path('config'),
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
        Broadcast::routes();

        require dirname(__DIR__, 1).'/routes/QueryChannel.php';
    }
}
