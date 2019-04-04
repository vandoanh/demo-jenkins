<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use App\Library\Services\CommonService;
use Illuminate\Http\JsonResponse;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 403);
            } else {
                session()->put('url.intended', $request->fullUrl());

                if ($guard === 'backend') {
                    return redirect(route('backend.auth.login'));
                }

                return redirect(route('auth.login'));
            }
        } else {
            Auth::shouldUse($guard);
        }

        return $next($request);
    }
}
