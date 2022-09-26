<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoggerController extends Controller
{
public function show_message(Request $request){
// GET Request only
if($request->isMethod('get')){
	return response()->json([
	"Error" => "This method is not supported",
	"Action" => "Use a POST request to log an arrival"
	], 200);
}
}

public function add_log(Request $request){
// Log the bus_arival time
// POST request using JSON format
// Containing Valid api_token and stop_id
// Containing client_type -> must be bus_stop
// Containing valid trip_id (CHNAGE TO RF-UID)
if($request->isMethod('post')){
	$arr_date = date("Y-m-d");
	$arr_time = date("H:i") ; // Log the time as soon as possible
	$content_type = strtolower($request->header('Content-Type'));
	if($content_type=='application/json'){
		$client_type = strtolower($request->input('client_type'));
		if($client_type=="bus_stop"){
			$stop_id = $request->input('stop_id');
			if(checkStopExists($stop_id) && checkApiTokenExists($stop_id, $client_type)){
			$api_token = $request->input('api_token');
				if(verifyApiToken($api_token, $client_type, $stop_id)){
					/* NEW CODE */
					$rf_id = $request->input('rf_id');
					$bus = getBusByRfId($rf_id);
					if(!is_null($bus)){
						// Bus exists. Valid RFID
						$trip = getCurrentTripOfBus($bus->bus_id);
						if(!is_null($trip)){
							$trip_id = $trip->trip_id;
							/* -- */
							//$trip_id = strtoupper($request->input('trip_id')); // Will be empty string if not avl in Request
							if(!is_null($trip_id) && checkTripExists($trip_id)){
								if(checkStopInRoute($trip_id, $stop_id)){
									if(checkSameDayLog($trip_id, $stop_id, $arr_date)){
									// If this trip has been logged already, ignore
										return response()->json([
											"Error" => "This trip was already logged"
										], 208); // Already Reported
									}
									else{
										DB::table('time_logs')->insert([
											'trip_id' => $trip_id,
											'stop_id' => $stop_id,
											'arrival_time' => $arr_time,
											'arrival_date' => $arr_date,
										]);
										$next_stop = getNextStop($trip_id, $stop_id);
										if(!$next_stop){
											// This is the last stop of the route
											// Remove Bus-Trip association
											DB::table('trips')
											->where('trip_id', $trip_id)
											->update(['bus_id' => NULL]);
										}
										else{
											$predicted_time = predictByTraffic($stop_id, $next_stop);
											cleanTripPredictions($trip_id, $next_stop, $arr_date);
											// Make new prediction entry
											DB::table('live_traffic_predictions')->insert([
												'trip_id' => $trip_id,
												'trip_date' => $arr_date,
												'predicted_time' => $predicted_time,
												'stop_id' => $next_stop,
											]);
										}
										return response()->json([
											"Success" => "Trip Time Logged",
											"Time" => $arr_time,
											"Date" => $arr_date
										], 202); // Accepted
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
									"Error" => "Invalid Trip ID"
								], 400); // Bad Request
							}
						}
						else{
							return response()->json([
								"Error" => "Corrupt RF-ID"
							], 400); // Bad Request
						}
					}
					else{
						return response()->json([
							"Error" => "Corrupted RF-ID"
						], 400); // Bad Request
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

?>
