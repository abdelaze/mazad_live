<?php 

namespace App\filters\products;

use Closure;

class CategoryFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('category_id')) {
            return $next($request);
        }

        return $next($request)->with(['category' , 'subcategory' , 'country' , 'state' , 'city' , 'user' , 'images:id,product_id,image'])->where(['status' => 1 ,'category_id' =>  request()->input('category_id')]);
    }
}