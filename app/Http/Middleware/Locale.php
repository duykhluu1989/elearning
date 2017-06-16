<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    public function handle($request, Closure $next)
    {
        if($request->hasCookie('lang'))
            app()->setLocale($request->cookie('lang'));
        else
            app()->setLocale('vi');

        return $next($request);
    }
}