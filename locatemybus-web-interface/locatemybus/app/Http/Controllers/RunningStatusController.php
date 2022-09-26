<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RunningStatusController extends Controller
{
  public function search(Request $request){
    return view('running-status-search');
  }

  public function show_status(Request $request, $trip_id){
    if(checkTripExists($trip_id)){
      if(checkTripRunning($trip_id)){
        $date_now = date("Y-m-d");
        $data = getRunningStatus($trip_id, $date_now);
        return view('running-status-show')->with($data);
      }
      return response("This trip is not currently running", 404);
    }
    else{
      return response("Invalid URL. Check and try again", 404);
    }
  }
}
