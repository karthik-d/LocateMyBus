<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('api-token', 'ApiTokenController@show_message');
Route::post('api-token', 'ApiTokenController@create');
Route::patch('api-token', 'ApiTokenController@renew');

Route::get('log-time', 'LoggerController@show_message');
Route::post('log-time', 'LoggerController@add_log');

Route::get('get-arrivals', 'LivePredictionController@show_message');
Route::post('get-arrivals', 'LivePredictionController@show_arrivals');

Route::get('set-trip-bus', 'TripBusController@show_message');
Route::post('set-trip-bus', 'TripBusController@assign_bus_for_trip');
