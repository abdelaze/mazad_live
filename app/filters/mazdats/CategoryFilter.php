<?php 

namespace App\filters\mazdats;

use Closure;

class CategoryFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('category_id')) {
            return $next($request);
        }

        return $next($request)->with(['category','subcategory' , 'user' , 'images:id,mazdat_id,image'])->where(['status' => 1 , 'is_closed' => 0 ,'category_id' =>  request()->input('category_id')]);
    }
}