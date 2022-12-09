<?php

namespace App\Http\Middleware;

use App\Helpers\Message;
use App\Models\Organisation\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        if (!auth()->user()) {
            Message::get(401);
        }

        if (auth()->user()->status === User::STATUS_INACTIVE) {
            Message::get(423);
        }

        return $next($request);
    }
}
