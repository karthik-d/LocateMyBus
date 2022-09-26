<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

if(!function_exists('generateApiToken')){
  function generateApiToken(){
    $existing_tokens = DB::table('api_tokens')
                        ->select('api_token')
                        ->get()
                        ->toArray();
    do{
      $token = Str::random(60);
    }while(in_array($token, $existing_tokens));
    return $token;
  }
}

if(!function_exists('getApiToken')){
  function getApiToken($owner_id, $owner_type){
    if($owner_type=="user"){
      $search_field = "user_email";
    }
    elseif($owner_type=="bus_stop"){
      $search_field = "stop_id";
    }
    $id = DB::table('api_owners')
                ->where($search_field, $owner_id)
                ->first()
                ->id;
    $api_token_row = DB::table('api_tokens')
                 ->where('owner_id', $id)
                 ->first();
    return $api_token_row->api_token;
  }
}

if(!function_exists('verifyApiToken')){
  function verifyApiToken($api_token_given, $owner_type, $owner_id){
    $api_token = getApiToken($owner_id, $owner_type);
    if(strcmp($api_token_given, $api_token)==0){
      return true;
    }
    else{
      return false;
    }
  }
}

 ?>
