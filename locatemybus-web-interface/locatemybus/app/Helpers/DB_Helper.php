<?php

// This file is included automatically using "autoload"

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// ---------------------------------------- USER -----------------------------------------

if (! function_exists('checkUserExists')) {
  function checkUserExists($email){
    $user = DB::table('users')->get()
            ->where('email',$email)
            ->first();
    return !is_null($user);
  }
}

if(! function_exists('isUserAdmin')) {
  function isUserAdmin($email){
    if(!checkUserExists($email)){
      return false;
    }
    $user = DB::table('users')
            ->where('email',$email)
            ->first();
    if(in_array($user->email, Config::get('constants.DATABASE.ADMIN_USER_EMAILS'))){
      return true;
    }
    else{
      return false;
    }
  }
}

if (! function_exists('checkUserPassword')) {
    function checkUserPassword($password, $email){
        $user = DB::table('users')
                ->where('email',$email)
                ->first();
        if(Hash::check($password, $user->password)){
          return true;
        }
        else{
          return false;
        }
    }
}

if(! function_exists('checkAgainstAdminPasswords')){
  function checkAgainstAdminPasswords($password){
    foreach (Config::get('constants.DATABASE.ADMIN_USER_EMAILS') as $admin_email) {
      if(checkUserPassword($password, $admin_email)){
        return true;
      }
      else{
        ;
      }
    }
    return false;
  }
}

// ---------------------------------------- API Token -----------------------------------------

if(! function_exists('checkApiTokenExists')){
  function checkApiTokenExists($owner_id, $owner_type){
    // The owner_id may be a User Email or Bus Stop
    if($owner_type == "user"){
      $search_field = "user_email";
    }
    elseif($owner_type == "bus_stop"){
      $search_field = "stop_id";
    }
    $api_owner = DB::table('api_owners')
                  ->where($search_field, $owner_id)
                  ->first();
    if(is_null($api_owner)){
      return false;
    }
    else{
      $api_token_row = DB::table('api_tokens')
                        ->where('owner_id',$api_owner->id)
                        ->first();
      $expiry_date = $api_token_row->expiry;
      if($expiry_date<=date('Y-m-d')){
        DB::table('api_owners')
        ->where('id',$api_owner->id)
        ->delete();   // Automatically cascades the API Owner table
        return false;  // Token Expired
      }
      else{
        return true;
      }
    }
  }
}

// ---------------------------------------- BUS STOPS -----------------------------------------

if (! function_exists('checkStopExists')) {
  function checkStopExists($stop_id){
    $stop = DB::table('stops')->get()
            ->where('stop_id', $stop_id)
            ->first();
    if(is_null($stop)){
      return false;
    }
    else{
      return((bool)$stop->is_active);
    }
  }
}

if(!function_exists('getStopSuggestionsForSearch')){
  function getStopSuggestionsForSearch($stop_name, $query){
    $suggestions = [
      "suggestions" => array(),
      "values" => array()
    ];
    $matches = DB::table('stops')
              ->select('stop_id', 'stop_name')
              ->where($stop_name, 'like', '%'.$query.'%') // Stop must be active
              ->where('is_active', 1)
              ->get()
              ->toArray();
    foreach ($matches as $stop) {
      array_push($suggestions['suggestions'], $stop->stop_name);
      array_push($suggestions['values'], $stop->stop_id);
    }
    return $suggestions;
  }
}

// ---------------------------------------- TRIPS -----------------------------------------

if(!function_exists('checkTripExists')){
  function checkTripExists($trip_id){
    $trip = DB::table('trips')
            ->where('trip_id', $trip_id)
            ->first();
    if(is_null($trip)){
      return false;
    }
    else{
      return((bool)$trip->is_active);
    }
  }
}

if(!function_exists('getTripsForRoute')){
  function getTripsForRoute($route_id){
    $trips = DB::table("trips")
              ->select('trip_id')
              ->where('route_id', $route_id)
              ->orderBy('sched_start_time', 'asc')
              ->get()
              ->toArray();
    $trip_ids = array();
    foreach($trips as $trp){
      array_push($trip_ids, $trp->trip_id);
    }
    return $trip_ids;
  }
}

if(!function_exists('checkTripRunning')){
  function checkTripRunning($trip_id, $date=null){
    // Must have started at origin and NOT reached destination
    if(is_null($date)){
      $date = date('Y-m-d');
    }
    $trip = DB::table('trips')
                ->select('route_id', 'is_onward')
                ->where('trip_id', $trip_id)
                ->where('is_active', 1)
                ->first();
    $route_row = DB::table('routes')
                ->select('origin', 'destination')
                ->where('route_id', $trip->route_id)
                ->first();
    $origin_logged = DB::table('time_logs')
                    ->where('stop_id', $route_row->origin)
                    ->where('arrival_date', $date)
                    ->where('trip_id', $trip_id)
                    ->first();
    $destn_logged = DB::table('time_logs')
                    ->where('stop_id', $route_row->destination)
                    ->where('arrival_date', $date)
                    ->where('trip_id', $trip_id)
                    ->first();
    if($trip->is_onward && is_null($destn_logged) && !is_null($origin_logged)){
		// Onward trip. Source logged. Destn not logged
      	return true;
    }
	elseif(!$trip->is_onward && !is_null($destn_logged) && is_null($origin_logged)){
		// Return trip. Source not logged. Destn logged
		return true;
	}
    else{
      return false;
    }
  }
}

if(!function_exists('checkTripToStart')){
  function checkTripToStart($trip_id, $date=null){
    // Must not have started and must not have ended
    if(is_null($date)){
      $date = date("Y-m-d");
    }
    $route_id = DB::table('trips')
                ->select('route_id')
                ->where('trip_id', $trip_id)
                ->where('is_active', 1)
                ->first()
                ->route_id;
    $route_row = DB::table('routes')
                ->select('origin', 'destination')
                ->where('route_id', $route_id)
                ->first();
    $origin_logged = DB::table('time_logs')
                    ->where('stop_id', $route_row->origin)
                    ->where('arrival_date', $date)
                    ->where('trip_id', $trip_id)
                    ->first();
    $destn_logged = DB::table('time_logs')
                    ->where('stop_id', $route_row->destination)
                    ->where('arrival_date', $date)
                    ->where('trip_id', $trip_id)
                    ->first();
    if(is_null($destn_logged) && is_null($origin_logged)){
      return true;
    }
    else{
      return false;
    }
  }
}

if(!function_exists('checkNoBusAssigned')){
	function checkNoBusAssigned($trip_id){
		$trip = DB::table('trips')
				->select('bus_id')
				->where('trip_id', $trip_id)
				->first();
		return is_null($trip->bus_id);
	}
}

if(!function_exists('getStopsOrderInTrip')){
  function getStopsOrderInTrip($stop_1, $stop_2, $trip_id){
    // Return 1 if stop 1 comes first
    $trip_row = DB::table('trips')
                ->select('route_id', 'is_onward')
                ->where('trip_id', $trip_id)
                ->first();
    $onward_order = getStopsOrderInRoute($stop_1, $stop_2, $trip_row->route_id);
    if($trip_row->is_onward==1){
      return $onward_order;
    }
    else{
      return (-1)*($onward_order);
    }
  }
}

if(!function_exists('getRunningTripsForRoute')){
  function getRunningTripsForRoute($route_id, $traveldate=null){
    if(is_null($traveldate)){
      $traveldate = date('Y-m-d');
    }
    $all_trips = getTripsForRoute($route_id);
    $running_trips = array();
    foreach($all_trips as $trip){
      if(checkTripRunning($trip, $traveldate)){
        array_push($running_trips, $trip);
      }
    }
    return $running_trips;
  }
}

if(!function_exists('getToStartTripsForRoute')){
  function getToStartTripsForRoute($route_id, $traveldate=null){
    if(is_null($traveldate)){
      $traveldate = date("Y-m-d");
    }
    $all_trips = getTripsForRoute($route_id);
    $tostart_trips = array();
    foreach($all_trips as $trip){
      if(checkTripToStart($trip, $traveldate)){
        array_push($tostart_trips, $trip);
      }
    }
    return $tostart_trips;
  }
}

if(!function_exists('getTripResultsForLiveSearch')){
  function getTripResultsForLiveSearch($start, $end){ //This is where the User wants to go
    $routes = getCommonRoutesForStops($start, $end);
    $results = [
      "routes" => array(),
      "trip_ids" => array(),
      "origins" => array(),
      "destinations" => array()
    ];
    foreach($routes as $rt){
      $route_row = DB::table('routes')
                    ->select('origin', 'destination')
                    ->where('route_id', $rt)
                    ->first();
      $is_onward = getStopsOrderInRoute($start, $end, $rt) + 1;
      // will be 0 if end comes before start, else 2
      if($is_onward){
        $route_start = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->origin)
                        ->first()
                        ->stop_name;
        $route_end = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->destination)
                        ->first()
                        ->stop_name;
      }
      else{
        $route_end = DB::table('stops')
                      ->select('stop_name')
                      ->where('stop_id', $route_row->origin)
                      ->first()
                      ->stop_name;
        $route_start = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->destination)
                        ->first()
                        ->stop_name;
      }
      foreach(getRunningTripsForRoute($rt) as $trip){
        if(getStopsOrderInTrip($start, $end, $trip)==1){
          // Direction is same as requestes. Stop 1 comes first
          array_push($results['routes'], $rt);
          array_push($results['trip_ids'], $trip);
          array_push($results['origins'], $route_start);
          array_push($results['destinations'], $route_end);
        }
      }
    }
    return $results;
  }
}

if(!function_exists('getTripResultsForPredictSearch')){
  function getTripResultsForPredictSearch($start, $end, $traveldate, $date_now){ //This is where the User wants to go
    $routes = getCommonRoutesForStops($start, $end);
    $results = [
      "running" => [
          "routes" => array(),
          "trip_ids" => array(),
          "origins" => array(),
          "destinations" => array()
        ],
        "not_started" =>[
            "routes" => array(),
            "trip_ids" => array(),
            "origins" => array(),
            "destinations" => array(),
            "start_times" => array(),   // Scheduled starting time
            "date" => $traveldate
        ]
    ];
    foreach($routes as $rt){
      $route_row = DB::table('routes')
                    ->select('origin', 'destination')
                    ->where('route_id', $rt)
                    ->first();
      $is_onward = getStopsOrderInRoute($start, $end, $rt) + 1;
      // will be 0 if end comes before start, else 2
      if($is_onward){
        $route_start = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->origin)
                        ->first()
                        ->stop_name;
        $route_end = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->destination)
                        ->first()
                        ->stop_name;
      }
      else{
        $route_end = DB::table('stops')
                      ->select('stop_name')
                      ->where('stop_id', $route_row->origin)
                      ->first()
                      ->stop_name;
        $route_start = DB::table('stops')
                        ->select('stop_name')
                        ->where('stop_id', $route_row->destination)
                        ->first()
                        ->stop_name;
      }
      foreach(getRunningTripsForRoute($rt, $traveldate) as $trip){
        if(getStopsOrderInTrip($start, $end, $trip)==1){
          // Direction is same as requested. Stop 1 comes first
          array_push($results['running']['routes'], $rt);
          array_push($results['running']['trip_ids'], $trip);
          array_push($results['running']['origins'], $route_start);
          array_push($results['running']['destinations'], $route_end);
        }
      }
      foreach(getToStartTripsForRoute($rt, $traveldate) as $trip){
        if(getStopsOrderInTrip($start, $end, $trip)==1){
          // Direction is same as requested. Stop 1 comes first
          $trip_sched_start = DB::table('trips')
                              ->select('sched_start_time')
                              ->where('trip_id', $trip)
                              ->first()
                              ->sched_start_time;
          array_push($results['not_started']['routes'], $rt);
          array_push($results['not_started']['trip_ids'], $trip);
          array_push($results['not_started']['origins'], $route_start);
          array_push($results['not_started']['destinations'], $route_end);
          array_push($results['not_started']['start_times'], substr($trip_sched_start, 0, 5));
        }
      }
    }
    return $results;
  }
}

if(!function_exists('getStopsInTrip')){
  function getStopsInTrip($trip_id){
    $trip_row = DB::table('trips')
                ->where('trip_id', $trip_id)
                ->first();
    $stops = getStopsInRoute($trip_row->route_id, $trip_row->is_onward);
    return $stops;
  }
}

if(!function_exists('getCurrentTripOfBus')){
	function getCurrentTripOfBus($bus_id){
		$trip = DB::table('trips')
				->where('bus_id', $bus_id)
				->first();
		return $trip;
	}
}

if(!function_exists('getTripById')){
	function getTripById($trip_id){
		$trip = DB::table('trips')
				->where('trip_id', $trip_id)
				->first();
		return $trip;
	}
}

// ---------------------------------------- BUS -----------------------------------------

if(!function_exists('getBusByRfId')){
	function getBusByRfId($rf_id){
		$bus = DB::table('buses')
				->where('rf_id', $rf_id)
				->first();
		return $bus;
	}
}

// ---------------------------------------- ROUTES -----------------------------------------

if(!function_exists('getNextStop')){   // Returns false if no next stop. Else returns next stop's id
  function getNextStop($trip_id, $stop_id){
    $trip = DB::table('trips')
              ->where('trip_id', $trip_id)
              ->first();
    $route = DB::table('routes')
              ->where('route_id',$trip->route_id)
              ->first();
    if($trip->is_onward){
      if(strcmp($route->destination, $stop_id)==0){
        return false;
      }
    }
    else{
      if(strcmp($route->origin, $stop_id)==0){
        return false;
      }
      //$next_stop_serial = $serial->onward_serial - 1;
    }
    $serial = DB::table('stop_sequences')
                ->select('onward_serial')
                ->where('stop_id', $stop_id)
                ->where('route_id', $route->route_id)
                ->first();
    if($trip->is_onward){
      $next_stop_serial = $serial->onward_serial + 1;
    }
    else{
      $next_stop_serial = $serial->onward_serial - 1;
    }
    $next_stop = DB::table('stop_sequences')
                  ->where('onward_serial', $next_stop_serial)
                  ->where('route_id', $route->route_id)
                  ->first()
                  ->stop_id;
    return $next_stop;
  }
}

if(!function_exists('getStopsOrderInRoute')){
  function getStopsOrderInRoute($stop_1, $stop_2, $route_id){
    // Return 1 if stop 1 comes first
    $stop_1_serial = DB::table('stop_sequences')
                    ->select('onward_serial')
                    ->where('route_id', $route_id)
                    ->where('stop_id', $stop_1)
                    ->first();
    $stop_2_serial = DB::table('stop_sequences')
                    ->select('onward_serial')
                    ->where('route_id', $route_id)
                    ->where('stop_id', $stop_2)
                    ->first();
    if($stop_1_serial < $stop_2_serial){
      return 1;
    }
    else{
      return -1;
    }
  }
}

if(!function_exists('getRoutesForStop')){
  function getRoutesForStop($stop_id){
    $routes = DB::table('stop_sequences')
              ->select('route_id')
              ->where('stop_id', $stop_id)
              ->get()
              ->toArray();
    $route_ids = array();
    foreach($routes as $rt){
      array_push($route_ids, $rt->route_id);
    }
    return $route_ids;
  }
}

if(!function_exists('checkStopInRoute')){
  function checkStopInRoute($trip_id, $stop_id){
    $route_id = DB::table('trips')
                ->where('trip_id', $trip_id)
                ->first()->route_id;
    $stops_enroute = DB::table('stop_sequences')
                     ->select('stop_id')
                     ->where('route_id',$route_id)
                     ->get()
                     ->toArray();
    $stop_ids = array();
    foreach ($stops_enroute as $stop) {
      array_push($stop_ids, $stop->stop_id);
    }
    return in_array($stop_id, $stop_ids);
  }
}

if(!function_exists('getCommonRoutesForStops')){
  function getCommonRoutesForStops($stop_1, $stop_2){
    $start_routes = getRoutesForStop($stop_1);
    $end_routes = getRoutesForStop($stop_2);
    $common_routes = array();
    foreach($start_routes as $st_pt){
      if(in_array($st_pt, $end_routes)){
        array_push($common_routes, $st_pt);
      }
    }
    return $common_routes;
  }
}

if(!function_exists('getStopsInRoute')){
  function getStopsInRoute($route_id, $is_onward){
    if($is_onward){
      $order = 'asc';
    }
    else{
      $order = 'desc';
    }
    $sequence = DB::table('stop_sequences')
                ->select('stop_id')
                ->where('route_id', $route_id)
                ->orderBy('onward_serial', $order)
                ->get()
                ->toArray();
    $stops = array();
    foreach($sequence as $stop){
      array_push($stops, $stop);
    }
    return $stops;
  }
}

// ---------------------------------------- GENERAL -----------------------------------------

if(!function_exists('getRunningStatus')){
  function getRunningStatus($trip_id, $date_now){
    $stops = getStopsInTrip($trip_id);
    $route_id = DB::table('trips')
                ->select('route_id')
                ->where('trip_id', $trip_id)
                ->first()
                ->route_id;
    // In same order as stops
    $logs = DB::table('time_logs')
            ->where('arrival_date', $date_now)
            ->where('trip_id', $trip_id)
            ->orderBy('arrival_time', 'asc')
            ->get()
            ->toArray();
    $stop_num_crossed = sizeof($logs);
    $status = [
      "stops" => array(),
      "times" => array(),
      "stop_crossed" => $stop_num_crossed,
      "date" => $date_now,
      "route" => $route_id
    ];
    for($i=0;$i<$stop_num_crossed;$i++){
      $stop_name = DB::table('stops')
                      ->select('stop_name')
                      ->where('stop_id', $stops[$i]->stop_id)
                      ->first()
                      ->stop_name;
      array_push($status['stops'], $stop_name);
      array_push($status['times'], substr($logs[$i]->arrival_time, 0, 5)); // Only upto minutes
    }
    $crossed_stop = $stops[$stop_num_crossed-1]; //Index is 1 less
    for($i=$stop_num_crossed;$i<sizeof($stops);$i++){
      $stop_name = DB::table('stops')
                      ->select('stop_name')
                      ->where('stop_id', $stops[$i]->stop_id)
                      ->first()
                      ->stop_name;
      array_push($status['stops'], $stop_name);
      array_push($status['times'], predictByTraffic($crossed_stop, $stops[$i]));
    }
    return $status;
  }
}

if(!function_exists('getExpectedSchedule')){
  function getExpectedSchedule($trip_id, $traveldate){
    $stops = getStopsInTrip($trip_id);
    $trip = DB::table('trips')
                ->select('route_id', 'is_onward')
                ->where('trip_id', $trip_id)
                ->first();
    $terminals = DB::table('routes')
              ->select('origin', 'destination')
              ->where('route_id', $trip->route_id)
              ->first();
	if($trip->is_onward){
		$origin = $terminals->origin;
	}
	else{
		$origin = $terminals->destination;
	}
    // In same order as stops
    $schedule = [
      "stops" => array(),
      "times" => array(),
      "date" => $traveldate,
      "route" => $trip->route_id
    ];
    for($i=0;$i<sizeof($stops);$i++){
		$stop_name = DB::table('stops')
						->select('stop_name')
						->where('stop_id', $stops[$i]->stop_id)
						->first()
						->stop_name;
		
		if(strcmp($stops[$i]->stop_id, $origin)==0){
			// FOR ORIGIN, DISPLAY SCHEDULE START TIME
			$trip_sched_start = DB::table('trips')
								->select('sched_start_time')
								->where('trip_id', $trip_id)
								->first()
								->sched_start_time;
			array_push($schedule['times'], substr($trip_sched_start, 0, 5));
     	}
      else{
        // FOR OTHER STOPS, DISPLAY PREDICTED TIMES
        array_push($schedule['times'], predictByModel($trip_id, $stops[$i]->stop_id, $traveldate));
      }
      array_push($schedule['stops'], $stop_name);

    }
    return $schedule;
  }
}
