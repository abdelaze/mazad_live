<?php

namespace App\Http\Middleware;

use Request;
use Illuminate\Support\Facades\App;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
       
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $lang    = App::getLocale();
        if ($request->is($lang.'/admin') || $request->is($lang.'/admin/*')) {
            return redirect()->route('get.admin.login');
        }else { 
            //return route('login');
            return redirect()->route('get.admin.login');
        }
       
        
    }
}
