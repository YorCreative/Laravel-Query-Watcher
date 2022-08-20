<?php

namespace YorCreative\QueryWatcher\Broadcasting;

class QueryEventBroadcast
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  string  $token
     * @return bool
     */
    public function join(string $token): bool
    {
        return config('querywatcher.token') == $token;
    }
}
