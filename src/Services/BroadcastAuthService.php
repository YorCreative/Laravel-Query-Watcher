<?php

namespace YorCreative\QueryWatcher\Services;

use Pusher\Pusher;
use Pusher\PusherException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;

class BroadcastAuthService
{
    /**
     * @param Request $request
     * @return Response|Application|ResponseFactory
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
