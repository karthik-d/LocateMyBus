<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ApiTokenController extends Controller
{

  public function show_message(Request $request){
    // GET Request only
    if($request->isMethod('get')){
      return response()->json([
        "Error" => "This method is not supported",
        "Action" => "Use a POST request to Create Token. Use a PATCH request to Renew Token"
      ], 200);
    }
  }

  public function create(Request $request){
  	// POST Request using JSON Data
  	// Containing email and password for users
  	// Containing stop_id and admin_password for bus stops
  	// Containing client_type as user, bus_stop
    // IF TOKEN ALREADY EXISTS, THE VALUE IS RESPONDED
    // This method is used to renew API Tokens after expiry using admin password
  	if($request->isMethod('post')){
  		$content_type = strtolower($request->header('Content-Type'));
  		if($content_type=='application/json'){
  			$client_type = strtolower($request->input('client_type'));
  			if($client_type=="user"){
  				$user_email = $request->input('email');
  				$user_pwd = $request->input('password');
  				if(checkUserExists($user_email) && checkUserPassword($user_pwd, $user_email)){
            if(checkApiTokenExists($user_email, $client_type)){
              $api_token = getApiToken($user_email, $client_type);
              return response()->json([
                "Error" => "This user already has a token",
                "API Token" => $api_token
              ], 200);
            }
            else{
              // Create an API Token api_owners
              $owner_id = DB::table('api_owners')->insertGetId([
                'user_email' => $user_email,
                'stop_id' => null
              ]);
              $api_token = generateApiToken();
              if(isUserAdmin($user_email)){
                $access_type = 'admin';
              }
              else{
                $access_type = 'public';
              }
              $addition_string = "+";
              $addition_string .= strval(Config::get('constants.API.TOKEN_VALIDITY_DAYS'));
              $addition_string .= " day";
              $expiry_date = date('Y-m-d',strtotime("{$addition_string}"));
              DB::table('api_tokens')->insert([
                'api_token' => $api_token,
                'owner_id' => $owner_id,
                'owner_type' => $client_type,
                'access_type' => $access_type,
                'expiry' => $expiry_date
              ]);
              return response()->json([
                "Success" => "API Token Generated",
                "API Token" => $api_token,
                "Expiry" => $expiry_date
              ], 201);
            }
          }
          else{
            return response()->json([
                "Error" => "Invalid Credentials"
              ], 401);
          }
        }
        elseif($client_type=="bus_stop"){
          $stop_id = $request->input('stop_id');
          $adm_pwd = $request->input('password');
          if(checkStopExists($stop_id) && checkAgainstAdminPasswords($adm_pwd)){
            if(checkApiTokenExists($stop_id, $client_type)){
              $api_token = getApiToken($stop_id, $client_type);
              return response()->json([
                "Error" => "This bus stop already has a token",
                "API Token" => $api_token
              ], 200);
            }
            else{
              // Create an API Token  and API Owner
              $owner_id = DB::table('api_owners')->insertGetId([
                'user_email' => null,
                'stop_id' => $stop_id
              ]);
              $api_token = generateApiToken();
              $access_type = 'bus_stop';
              $addition_string = "+";
              $addition_string .= strval(Config::get('constants.API.TOKEN_VALIDITY_DAYS'));
              $addition_string .= " day";
              $expiry_date = date('Y-m-d',strtotime("{$addition_string}"));
              DB::table('api_tokens')->insert([
                'api_token' => $api_token,
                'owner_id' => $owner_id,
                'owner_type' => $client_type,
                'access_type' => $access_type,
                'expiry' => $expiry_date
              ]);
              return response()->json([
                "Success" => "API Token Generated",
                "API Token" => $api_token,
                "Expiry" => $expiry_date
              ], 201);
            }
          }
          else{
            return response()->json([
                "Error" => "Invalid Credentials"
              ], 401);
          }
        }
        else{
          return response()->json([
              "Error" => "Unknown Client Type"
            ], 400);
        }
      }
      else{
        return response()->json([
          "Error" => "Unknown format. Use JSON",
        ], 400);
      }
    }
  }

  public function renew(Request $request){
    // PATCH request using JSON format
    // Containing Valid api_token and stop_id(for bus_stop) or email(for user)
    // Containing client_type
    if($request->isMethod('patch')){
      $curr_date = date("Y-m-d");
      $content_type = strtolower($request->header('Content-Type'));
  		if($content_type=='application/json'){
  			$client_type = strtolower($request->input('client_type'));
        if($client_type=="bus_stop"){
          $stop_id = $request->input('stop_id');
          if(checkStopExists($stop_id) && checkApiTokenExists($stop_id, $client_type)){
            $api_token = $request->input('api_token');
            if(verifyApiToken($api_token, $client_type, $stop_id)){
              $api_token_new = generateApiToken();
              $addition_string = "+";
              $addition_string .= strval(Config::get('constants.API.TOKEN_VALIDITY_DAYS'));
              $addition_string .= " day";
              $expiry_date = date('Y-m-d',strtotime("{$addition_string}"));
              DB::table('api_tokens')
              ->where('api_token', $api_token)
              ->update(['api_token' => $api_token_new]);
              return response()->json([
                "Success" => "New Token Generated",
                "API Token" => $api_token_new,
                "Expiry" => $expiry_date
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
              "Error" => "Invalid or Expired API Token. Generate new with admin password"
            ], 401); // Unauthorized
          }
        }
        elseif($client_type=="user"){
  				$user_email = $request->input('email');
  				if(checkUserExists($user_email) && checkApiTokenExists($user_email, $client_type)){
            $api_token = $request->input('api_token');
            if(verifyApiToken($api_token, $client_type, $user_email)){
              $api_token_new = generateApiToken();
              $addition_string = "+";
              $addition_string .= strval(Config::get('constants.API.TOKEN_VALIDITY_DAYS'));
              $addition_string .= " day";
              $expiry_date = date('Y-m-d',strtotime("{$addition_string}"));
              DB::table('api_tokens')
              ->where('api_token', $api_token)
              ->update(['api_token' => $api_token_new]);
              return response()->json([
                "Success" => "New Token Generated",
                "API Token" => $api_token_new,
                "Expiry" => $expiry_date
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
              "Error" => "Invalid or Expired API Token. Generate new with admin password"
            ], 401); // Unauthorized
          }
        }
        else{
          return response()->json([
              "Error" => "Unknown Client Type"
            ], 400);
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
