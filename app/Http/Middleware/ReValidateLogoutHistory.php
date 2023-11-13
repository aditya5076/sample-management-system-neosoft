<?php

namespace App\Http\Middleware;

use Closure;

class ReValidateLogoutHistory
{
    /**
     * Handle an incoming request. This middleware re-validates logout and prevents back button caching & history of browser behaviour.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @author Akash Puthanekar <akash.puthanekar@neosofttech.com>
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
