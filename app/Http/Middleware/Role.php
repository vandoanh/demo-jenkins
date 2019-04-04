<?php
namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Check role
     *
     * @param unknown $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        return $response;
    }
}
