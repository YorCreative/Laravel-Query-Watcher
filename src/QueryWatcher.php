<?php

namespace YorCreative\QueryWatcher;

use Illuminate\Support\Facades\DB;
use YorCreative\QueryWatcher\Events\QueryEvent;

class QueryWatcher
{
    /**
     * @return void
     */
    public static function listen(): void
    {
        if (config('querywatcher.enabled')) {
            DB::enableQueryLog();

            DB::listen(function ($query) {
                $time_exceeds_ms_enabled = self::timeExceedsMsEnabled();

                if (isset($time_exceeds_ms_enabled)
                    && self::getTimeExceedsMs() < $query->time
                    || ! isset($time_exceeds_ms_enabled)) {
                    event(new QueryEvent($query));
                }
            });
        }
    }

    /**
     * @return bool
     */
    public static function timeExceedsMsEnabled(): bool
    {
        return config('querywatcher.scope.time_exceeds_ms.enabled') ?? false;
    }

    /**
     * @return float|null
     */
    public static function getTimeExceedsMs(): ?float
    {
        return config('querywatcher.scope.time_exceeds_ms.threshold') ?? 0;
    }
}
