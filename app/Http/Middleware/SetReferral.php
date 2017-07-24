<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use App\Libraries\Helpers\Utility;
use App\Models\User;
use App\Models\Collaborator;

class SetReferral
{
    public function handle($request, Closure $next)
    {
        if($request->has('referral'))
        {
            if($request->hasCookie(Utility::REFERRAL_COOKIE_NAME) == false || $request->input('referral') != $request->cookie(Utility::REFERRAL_COOKIE_NAME))
            {
                $referral = User::select('user.id')
                    ->join('collaborator', 'user.id', '=', 'collaborator.user_id')
                    ->where('user.status', Utility::ACTIVE_DB)
                    ->where('collaborator.status', Collaborator::STATUS_ACTIVE_DB)
                    ->where('collaborator.code', $request->input('referral'))
                    ->first();

                if(!empty($referral))
                    Cookie::queue(Cookie::make(Utility::REFERRAL_COOKIE_NAME, $request->input('referral'), Utility::MINUTE_ONE_MONTH * 2));
            }
        }

        return $next($request);
    }
}