<?php

namespace YorCreative\QueryWatcher\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Transformers\QueryTransformer;

class QueryEvent extends Event implements ShouldBroadcast
{
    /**
     * @var QueryModel
     */
    public QueryModel $queryModel;

    /**
     * @param $query
     */
    public function __construct(QueryExecuted $query)
    {
        $this->queryModel = (new QueryModel(QueryTransformer::transform($query)));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('query.event.'.config('querywatcher.token'));
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'query.event';
    }
}
