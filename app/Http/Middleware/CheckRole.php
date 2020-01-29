<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if($user->hasRole('administrator')){
            return $next($request);
        }
        else{
            Auth::logout();
           return \Redirect::back()->withWarning( 'Please check with administrator' );
        }
        
    }
}
