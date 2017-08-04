<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use App\Models\Collaborator;

class CollaboratorAccess
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if(empty($user->collaboratorInformation) || $user->collaboratorInformation->status == Collaborator::STATUS_PENDING_DB)
            return response()->view('frontend.errors.404');

        return $next($request);
    }
}
