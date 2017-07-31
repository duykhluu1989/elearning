<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use App\Libraries\Helpers\Utility;

class SetVisitStartTime
{
    public function handle($request, Closure $next)
    {
        if($request->hasCookie(Utility::VISIT_START_TIME_COOKIE_NAME) == false)
            Cookie::queue(Cookie::make(Utility::VISIT_START_TIME_COOKIE_NAME, time(), Utility::MINUTE_ONE_DAY / 12));

        return $next($request);
    }
}