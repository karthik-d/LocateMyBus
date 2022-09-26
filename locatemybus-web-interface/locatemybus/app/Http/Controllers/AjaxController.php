<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxController extends Controller
{
  public function show_suggestions(Request $request){
    $content_type = strtolower($request->header('Content-Type'));
    if($content_type=='application/json'){
      $search_id = $request->input('search_id');
      $query = $request->input('query');
      $suggestions = getStopSuggestionsForSearch($search_id, $query);
      return response()->json($suggestions, 200); // OK
    }
    else{
      return response()->json([
        "Error" => "Unknown format. Use JSON",
      ], 400); // Bad Request
    }
  }

  public function show_results_live(Request $request){
    $content_type = strtolower($request->header('Content-Type'));
    if($content_type=='application/json'){
      $search_type = $request->input('search_type');
      if($search_type=='trips'){
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        if(strcmp($origin, $destination)==0){
          $results = [
            "routes" => array(),
            "trip_ids" => array(),
            "origins" => array(),
            "destinations" => array()
          ];
          return response()->json($results, 200); // OK
        }
        if(!checkStopExists($origin) || !checkStopExists($origin)){
          $results = [
            "routes" => array(),
            "trip_ids" => array(),
            "origins" => array(),
            "destinations" => array()
          ];
          return response()->json($results, 200); // OK
        }
        $results = getTripResultsForLiveSearch($origin, $destination);
        return response()->json($results, 200); // OK
      }
      else{
        return response()->json([
          "Error" => "Unknown search type",
        ], 400); // Bad Request
      }
    }
    else{
      return response()->json([
        "Error" => "Unknown format. Use JSON",
      ], 400); // Bad Request
    }
  }

  public function show_results_prediction(Request $request){
    $content_type = strtolower($request->header('Content-Type'));
    if($content_type=='application/json'){
      $search_type = $request->input('search_type');
      if($search_type=='trips'){
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $traveldate = $request->input('traveldate');
        if(strcmp($origin, $destination)==0){
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
                  "start_times" => array()
              ]
          ];
          return response()->json($results, 200); // OK
        }
        $date_now = date('Y-m-d');
        if($traveldate>=$date_now && checkDateFormat($traveldate)){
          $results = getTripResultsForPredictSearch($origin, $destination, $traveldate, $date_now);
          return response()->json($results, 200); // OK
        }
        else{
          return response()->json([
            "Error" => "Invalid Date",
          ], 400); // Bad Request
        }
      }
      else{
        return response()->json([
          "Error" => "Unknown search type",
        ], 400); // Bad Request
      }
    }
    else{
      return response()->json([
        "Error" => "Unknown format. Use JSON",
      ], 400); // Bad Request
    }
  }
}
