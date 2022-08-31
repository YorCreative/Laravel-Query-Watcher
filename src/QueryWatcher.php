<?php

namespace YorCreative\QueryWatcher;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use YorCreative\QueryWatcher\Events\QueryEvent;

class QueryWatcher
{
    /**
     * @return void
     */
    public static function listen(): void
    {
        ! self::isQueryWatcherEnabled() ?:
            DB::listen(function ($query) {
                $time_exceeds_ms_enabled = self::timeExceedsMsEnabled();

                if ($time_exceeds_ms_enabled && self::getTimeExceedsMs() < $query->time
                    || !$time_exceeds_ms_enabled
                    && !self::ignorable($query)) {

                    // only capture queries if:
                    //     - time_exceeds_ms is not enabled
                    //     - time_exceeds_ms is enabled and query time exceeds the threshold.
                    //     - query isn't on table that is ignorable.

                    event(new QueryEvent($query));
                }
            });
    }

    public static function isQueryWatcherEnabled()
    {
        return Config::get('querywatcher.enabled') ?? false;
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

    /**
     * @param QueryExecuted $queryExecuted
     * @return bool
     */
    public static function ignorable(QueryExecuted $queryExecuted): bool
    {
        // check if query is on table that is ignorable
        foreach (Config::get('querywatcher.scope.ignorable_tables') as $table) {
            if(preg_match('~(from "'.$table .'"|update "'.$table.'"|into "'.$table.'")~i',$queryExecuted->sql)) {
                return true;
            }
        }

        // check if query includes ignorable statement
        foreach (Config::get('querywatcher.scope.ignorable_statements') as $statement) {
            if (str_starts_with(strtolower($queryExecuted->sql), strtolower($statement))) {
                return true;
            }
        }

        return false;
    }
}
