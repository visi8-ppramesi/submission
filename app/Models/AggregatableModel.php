<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AggregatableModel extends Model{
    public static function getAggregatedRecords($startDate, $endDate, $aggName){
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $acts = self::whereBetween($aggName, [$startDate, $endDate])->get();
        $dates = array_map(function($date) use ($startDate) {
            return (strtotime(date('Y-m-d', strtotime($date))) - strtotime(date('Y-m-d', strtotime($startDate))))/(24*60*60);
        }, $acts->pluck($aggName)->toArray());
        $dateCounts = array_count_values($dates);
        $dateAct = [];
        $daysLength = (strtotime(date('Y-m-d', strtotime($endDate))) - strtotime(date('Y-m-d', strtotime($startDate))))/(24*60*60);
        for($n = 0; $n < $daysLength; $n++){
            if(array_key_exists(strval($n), $dateCounts)){
              $dateAct[$n] = $dateCounts[strval($n)];
            }else{
              $dateAct[$n] = 0;
            }
        }
        return $dateAct;
    }
}
