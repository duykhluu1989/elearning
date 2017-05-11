<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class Permission
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();


        return $next($request);
    }
}
