<?php 

namespace App\filters\mazdats;

use Closure;

class NameFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('search')) {
            return $next($request);
        }

        return $next($request)->with(['category','subcategory' , 'user' , 'images:id,mazdat_id,image'])->where(['status' => 1 , 'is_closed' => 0 ])->where('product_name','like', '%'.request()->input('search').'%')->orWhere('product_desc','like', '%'.request()->input('search').'%');
    }
}