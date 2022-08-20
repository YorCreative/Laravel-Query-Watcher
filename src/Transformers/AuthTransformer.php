<?php

namespace YorCreative\QueryWatcher\Transformers;

use Illuminate\Support\Facades\Auth;

class AuthTransformer
{
    /**
     * @return array[]
     */
    public static function transform(): array
    {
        return [
            'check' => Auth::check(),
            'id' => Auth::check() ? Auth::user()->getAuthIdentifier() : null,
            'model' => Auth::check() ? Auth::user()->getMorphClass() : null,

        ];
    }
}
