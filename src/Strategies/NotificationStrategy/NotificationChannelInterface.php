<?php

namespace YorCreative\QueryWatcher\Strategies\NotificationStrategy;

use YorCreative\QueryWatcher\Models\QueryModel;

interface NotificationChannelInterface
{
    public function isEnabled(): bool;

    public function notify(QueryModel $model): void;
}
