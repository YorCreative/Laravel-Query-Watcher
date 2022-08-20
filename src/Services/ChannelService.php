<?php

namespace YorCreative\QueryWatcher\Services;

use Illuminate\Support\Collection;
use YorCreative\QueryWatcher\Models\QueryModel;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\Channels\Discord;
use YorCreative\QueryWatcher\Strategies\NotificationStrategy\NotificationStrategy;

class ChannelService
{
    /**
     * @param  QueryModel  $model
     */
    public static function notify(QueryModel $model): void
    {
        $notificationStrategy = new NotificationStrategy();

        self::getChannels()->each(function ($channel) use (&$notificationStrategy) {
            if ($channel->isEnabled()) {
                $notificationStrategy->setChannel($channel);
            }
        });

        $notificationStrategy->notify($model);
    }

    /**
     * @return Collection
     */
    protected static function getChannels(): Collection
    {
        return new Collection([
            new Discord(),
        ]);
    }
}
