<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LivePredictionController extends Controller
{

  public function show_message(Request $request){
    // GET Request only
    if($request->isMethod('get')){
      return response()->json([
        "Error" => "This method is not supported",
        "Action" => "Use a POST request to get arrivals list"
      ], 200);
    }
  }

  public function show_arrivals(Request $request){
    // POST request using JSON format
    // Containing Valid api_token and stop_id
    // Containing client_type -> must be bus_stop
    if($request->isMethod('post')){
      $curr_date = date("Y-m-d");
      $content_type = strtolower($request->header('Content-Type'));
  		if($content_type=='application/json'){
  			$client_type = strtolower($request->input('client_type'));
        if($client_type=="bus_stop"){
          $stop_id = $request->input('stop_id');
          if(checkStopExists($stop_id) && checkApiTokenExists($stop_id, $client_type)){
            $api_token = $request->input('api_token');
            if(verifyApiToken($api_token, $client_type, $stop_id)){
              $arrivals = generateArrivals($stop_id, $curr_date);
              return response()->json([
                "Success" => "Arrival List Ready",
                "Arrivals" => $arrivals
              ], 200);  // OK
            }
            else{
              return response()->json([
                "Error" => "Invalid or Expired API Token"
              ], 401); // Unauthorized
            }
          }
          else{
            return response()->json([
              "Error" => "Invalid or Expired API Token"
            ], 401); // Unauthorized
          }
        }
        else{
          return response()->json([
              "Error" => "This Client is Not Allowed"
            ], 401);  //Unauthorized
        }
      }
      else{
        return response()->json([
          "Error" => "Unknown format. Use JSON",
        ], 400);
      }
    }
  }
}
