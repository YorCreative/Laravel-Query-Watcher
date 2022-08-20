<?php

namespace YorCreative\QueryWatcher\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use YorCreative\QueryWatcher\Events\QueryEvent;
use YorCreative\QueryWatcher\Services\ChannelService;

class QueryListener implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     *
     * @var string|null
     */
    public $connection;

    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue;

    public function __construct()
    {
        $this->connection = config('querywatcher.listener.connection');
        $this->queue = config('querywatcher.listener.queue');
    }

    /**
     * @param  QueryEvent  $event
     */
    public function handle(QueryEvent $event)
    {
        ChannelService::notify($event->queryModel);
    }
}
