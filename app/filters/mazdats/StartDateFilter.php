<?php 

namespace App\filters\mazdats;

use Closure;
use Carbon\Carbon;

class StartDateFilter
{

    public function handle($request, Closure $next)
    {
        if (!request()->has('start_date') && empty(request()->input('start_date'))) {
            return $next($request);
        }
        $from         = date('Y-m-d', strtotime(request()->input('start_date')));
        $from_time     = date('H:i', strtotime(request()->input('start_date')));
        return $next($request)->with(['category' ,'subcategory' , 'user' , 'images:id,mazdat_id,image'])
                               ->where(['type' => 2 , 'status' => 1 , 'is_closed' => 0])
                               ->whereDate('display_date', '>=',  $from)
                               ->whereTime('display_date', '>=', $from_time)
                               ->WhereDate('display_date', '>=',date('Y-m-d H:i:s' , strtotime(Carbon::create(request()->input('start_date'))->toDateString())))
                               ->where([ 'status' => 1 , 'is_closed' => 0]);
        
    }
}