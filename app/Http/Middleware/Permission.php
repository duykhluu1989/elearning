<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class Permission
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if($user->status == User::STATUS_INACTIVE_DB)
        {
            auth()->logout();

            return redirect()->action('Backend\UserController@login');
        }
        else
        {
            if($user->admin == false)
                return redirect('/');
        }

        return $next($request);
    }
}
