<?php

namespace YorCreative\QueryWatcher\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Pusher\Pusher;
use Pusher\PusherException;

class BroadcastAuthService
{
    /**
     * @param  Request  $request
     * @return Response|Application|ResponseFactory
     *
     * @throws PusherException
     */
    public static function pusher(Request $request): Response|Application|ResponseFactory
    {
        $pusher = app(Pusher::class);

        $auth = $pusher->authorizeChannel(
            $request->input('channel_name'),
            $request->input('socket_id')
        );

        return response($auth, 200);
    }
}
