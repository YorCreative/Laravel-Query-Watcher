<?php

use Illuminate\Support\Facades\Broadcast;
use YorCreative\QueryWatcher\Broadcasting\QueryEventBroadcast;

Broadcast::channel('query.event.{token}', QueryEventBroadcast::class);
