<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $user =  response()->json(Auth::guard()->user());
            if($user->getData()->role === 1){
                if($user->getData()->banned === 1)
                {
                    Auth::logout();
                    return Redirect(route('tokenNotExist'));
                }
                return $next($request);
            }
            return Redirect(route('unauthorized'));
        }catch(Exception $e){
            return Redirect(route('tokenNotExist'));
        }   
       
    }
}
