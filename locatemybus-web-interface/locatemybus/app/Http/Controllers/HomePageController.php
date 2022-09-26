<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomePageController extends Controller
{
  public function display_home(Request $request){
    $data = ['var'=>'value'];
    return view('home')->with($data);
  }
}
