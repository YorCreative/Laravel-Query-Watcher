<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use YorCreative\QueryWatcher\Services\BroadcastAuthService;

Route::post('/broadcasting/query-watcher/{driver}/auth', function (Request $request, $driver) {
    return match ($driver) {
        'pusher' => BroadcastAuthService::pusher($request),
        default => response([], 401),
    };
})->name('broadcasting.pusher.auth');
