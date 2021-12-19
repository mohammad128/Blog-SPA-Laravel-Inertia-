<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaDashboardShareData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
//        $user_permissons = [
//            'post_create', 'post_read'
//        ];
//        Inertia::share('can', $user_permissons);
        return $next($request);
    }
}
