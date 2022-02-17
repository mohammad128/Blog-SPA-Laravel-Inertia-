<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HasPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Inertia\Response
     */
    public function handle(Request $request, Closure $next, $className, $column='slug')
    {
        $r = explode('/', $request->path());
        $id = $r[count($r)-1];
        $model = app($className)->firstWhere($column, $id);
        if ( $model->password) {
            if( ! \Illuminate\Support\Facades\Session::get($className.$column.$id) )
                return Inertia::render('HasPassword',[
                    'className' => $className,
                    'column' => $column,
                    'id' => $id
                ]);
        }
        $result = $next($request);
        return $result;
    }
}
