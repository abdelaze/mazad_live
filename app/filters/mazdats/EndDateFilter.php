<?php 

namespace App\filters\mazdats;

use Closure;

class EndDateFilter
{
    public function handle($request, Closure $next)
    {
        if (!request()->has('end_date') && empty(request()->input('end_date'))) {
            return $next($request);
          
        }
//dd('ddasfasas');
        $to         = date('Y-m-d', strtotime(request()->input('end_date')));
        $to_time     = date('H:i', strtotime(request()->input('end_date')));

        return $next($request)->with(['category' ,'subcategory' , 'user' , 'images:id,mazdat_id,image'])
                              ->where(['status' => 1 , 'is_closed' => 0])
                              ->whereDate('display_date', '<=',  $to)
                              ->whereTime('display_date', '<=', $to_time)
                              ->OrWhereDate('display_date', '<=', $to)
                              ->where(['status' => 1 , 'is_closed' => 0]);
    }
}