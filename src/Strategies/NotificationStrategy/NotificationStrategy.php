<?php

namespace YorCreative\QueryWatcher\Strategies\NotificationStrategy;

use Illuminate\Support\Collection;
use YorCreative\QueryWatcher\Models\QueryModel;

class NotificationStrategy
{
    /**
     * @var Collection
     */
    private Collection $channels;

    public function __construct()
    {
        $this->channels = new Collection();
    }

    /**
     * @param  NotificationChannelInterface  $channel
     */
    public function setChannel(NotificationChannelInterface $channel): void
    {
        $this->channels->add($channel);
    }

    public function notify(QueryModel $model): void
    {
        $this->getChannels()->each(function ($channel) use ($model) {
            $channel->notify($model);
        });
    }

    /**
     * @return Collection
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }
}
