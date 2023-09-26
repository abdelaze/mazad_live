<?php 

namespace App\filters\mazdats;

use Closure;

class SubCategoryFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('subcategory_id')) {
            return $next($request);
        }

        return $next($request)->with(['category' ,'subcategory' , 'user' , 'images:id,mazdat_id,image'])->where([ 'status' => 1 , 'is_closed' => 0 ,'subcategory_id' =>  request()->input('subcategory_id')]);
    }
}