<?php

// This file is included automatically using "autoload"

use Illuminate\Support\Facades\DB;

if (! function_exists('checkUserExists')) {
  function checkUserExists($email){
    $user = DB::table('users')
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

if(! function_exists('checkApiTokenExists')){
  function checkApiTokenExists($email){
    $api_owner = DB::table('api_owners')
                  ->where('user_email', $email)
                  ->first();
    return !is_null($api_owner);              
  }
}
