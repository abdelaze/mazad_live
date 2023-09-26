<?php 

namespace App\filters\products;

use Closure;

class NameFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('search')) {
            return $next($request);
        }

        return $next($request)->with(['category' , 'subcategory' , 'country' , 'state' , 'city' , 'user' , 'images:id,product_id,image'])->where(['status' => 1 ])->where('product_name','like', '%'.request()->input('search').'%')->orWhere('product_desc','like', '%'.request()->input('search').'%');
    }
}