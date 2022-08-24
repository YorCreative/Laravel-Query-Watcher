<?php

namespace YorCreative\QueryWatcher\Transformers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class AuthTransformer
{
    /**
     * @return array[]
     */
    public static function transform(): array
    {
        $ttl = (int) Config::get('querywatcher.scope.context.auth_user.ttl');

        return Cache::remember(Session::getId(), $ttl, function () {
            return [
                'check' => Auth::check(),
                'id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
                'model' => Auth::check() ? Auth::user()->getMorphClass() : null,
            ];
        });
    }
}
