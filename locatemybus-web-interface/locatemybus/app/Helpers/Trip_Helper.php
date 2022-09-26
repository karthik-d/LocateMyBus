<?php

use Illuminate\Support\Facades\DB;

if(!function_exists('checkSameDayLog')){
  function checkSameDayLog($trip_id, $curr_stop, $date_now){
    $trip = DB::table('time_logs')
            ->where('trip_id', $trip_id)
            ->where('stop_id', $curr_stop)
            ->where('arrival_date', $date_now)
            ->first();
    return !is_null($trip);
  }
}

if(!function_exists('predictByTraffic')){
  function predictByTraffic($curr_stop, $target_stop){
    // ANY TWO STOPS

    // QUERY THE MAPS API TO GET TIME

    $predicted_time = date('H:i',strtotime('+15 minutes'));
	$predicted_time = '<time>';
    return $predicted_time;
  }
}

if(!function_exists('cleanTripPredictions')){
  function cleanTripPredictions($trip_id, $next_stop, $date_now){
    DB::table('live_traffic_predictions')
    ->where('trip_id', $trip_id)
    ->where('stop_id', $next_stop)
    ->where('trip_date','<=',$date_now)  // Removes previous or duplicate entries
    ->delete();                   // Only one entry per trip per stop will exist
  }
}

if(!function_exists('generateArrivals')){
  function generateArrivals($stop_id, $date_now){
    $predictions = DB::table('live_traffic_predictions')
                  ->select('trip_id', 'predicted_time')
                  ->where('stop_id', $stop_id)
                  ->where('trip_date', $date_now)
                  ->get()
                  ->toArray();
    $arrivals = array();
    foreach($predictions as $pred){
      $route_id = DB::table('trips')
                  ->where('trip_id', $pred->trip_id)
                  ->first()
                  ->route_id;
      $logs = DB::table('time_logs')
              ->where('trip_id', $pred->trip_id)
              ->where('stop_id', $stop_id)
              ->where('arrival_date', $date_now)
              ->first();
      if(is_null($logs)){
          array_push($arrivals, array($route_id, $pred->predicted_time));
      }
      else{
        // Don't consider. Bus has already arrived here
        ;
      }
    }
    return $arrivals;
  }
}

if(!function_exists('predictByModel')){
  function predictByModel($trip_id, $target_stop, $traveldate){

    // PREDICT USING THE LEARNT WEIGHTS

    $predicted_time = date('H:i',strtotime('+15 minutes'));
	$predicted_time = '<time>';
    return $predicted_time;
  }
}

 ?>
