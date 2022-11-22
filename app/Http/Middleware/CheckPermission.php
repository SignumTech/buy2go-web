<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
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
        if(Auth::check()){
            $type = auth()->user()->account_type;
            if($type != "Staff"){
                Auth::logout();

                $request->session()->invalidate();
            
                $request->session()->regenerateToken();
            
                return redirect('/');
            }
            return $next($request);            
        }
        else{
            return redirect('/');
        }

    }
}
