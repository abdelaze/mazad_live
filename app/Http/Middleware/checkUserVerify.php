<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkUserVerify
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
        if (Auth::guard('api')->user()) {
                 if (Auth::guard('api')->user()->isVerified  ==  0) {

                         $response = [
                             'success' => false,
                             'data'    => null,
                             'message' => 'You Must Verify Account First',
                         ];
                         return response()->json($response, 304);
                 }else {

                     return $next($request);
                 }

        }   
        return $next($request);
    }
}
