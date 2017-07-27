<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use App\Libraries\Helpers\Utility;
use App\Models\Setting;

class VisitorCount
{
    public function handle($request, Closure $next)
    {
        if($request->hasCookie(Utility::VISITOR_COOKIE_NAME) == false)
        {
            Setting::increaseVisitorCount();

            Cookie::queue(Cookie::make(Utility::VISITOR_COOKIE_NAME, $request->ip(), Utility::MINUTE_ONE_DAY));
        }

        return $next($request);
    }
}