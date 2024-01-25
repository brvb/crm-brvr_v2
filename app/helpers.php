<?php

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;

if (! function_exists('getCustomerConfig')) {
    function global_tenancy_asset($path)
    {
        return URL::to('/') . '/cl/' . tenant('id') . $path;
    }

    function global_hours_format($hour)
    {
        return strtok($hour,':').__("h ").substr($hour, strpos($hour, ":") + 1);
    }

    function global_hours_format_descontos($hour)
    {
        return strtok($hour,':').__(":").substr($hour, strpos($hour, ":") + 1);
    }

    function global_hours_sum($get_hours)
    {
        $sum_minutes = 0;

      
        foreach($get_hours as $hour)
        {
            if($hour->total_hours == "")
            {
                $hour->total_hours = "00:00";
            }
            $explodedTime = array_map('intval',explode(':',$hour->total_hours));
            $sum_minutes += $explodedTime[0]*60+$explodedTime[1];
        }
            

        if(strlen(floor($sum_minutes/60)) == 1){
            $hoursCheck = '0'.floor($sum_minutes/60);
        }
        else {
            $hoursCheck = floor($sum_minutes/60);
        }

        if(strlen(floor($sum_minutes % 60)) == 1){
            $minutesCheck = '0'.floor($sum_minutes % 60);
        }
        else {
            $minutesCheck = floor($sum_minutes % 60);
        }

        $sumTime = $hoursCheck. ':' .$minutesCheck;


        return global_hours_format($sumTime);
    }

    function get_day_name($timestamp) {

        $date = $timestamp;

        if(date('y-m-d',strtotime($timestamp)) == date('y-m-d',strtotime("now"))) {
          $date = 'Hoje, '.date("H:i",strtotime($date));
        } 
        else if(date('y-m-d',strtotime($timestamp)) == date('y-m-d',strtotime("-1 day"))) {
          $date = 'Ontem, '.date("H:i",strtotime($date));
        }

        return $date;
    }

    

}


