<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchedulePredictController extends Controller
{
  public function search(Request $request){
    $date_now = date('Y-m-d');
    $data = ["date_now" => $date_now];
    return view('schedule-prediction-search')->with($data);
  }

  public function show_status(Request $request, $trip_id, $traveldate){
    if(checkTripExists($trip_id)){
      $traveldate = makeDateFromString($traveldate);
      $date_now = date('Y-m-d');
      if($traveldate>=$date_now && checkDateFormat($traveldate)){
        if(checkTripToStart($trip_id, $traveldate)){
          $data = getExpectedSchedule($trip_id, $traveldate);
          return view('schedule-prediction-show')->with($data);
        }
        else{
          return response("Invalid URL. Please Check and Try Again", 404);
        }
      }
      else{
        return response("Invalid URL. Please Check and Try Again", 404);
      }
    }
    else{
      return response("Invalid URL. Please Check and Try Again", 404);
    }
  }
}
