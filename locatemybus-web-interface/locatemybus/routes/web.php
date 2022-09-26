<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'HomePageController@display_home');

Route::get('/running-status', 'RunningStatusController@search');
Route::get('/running-status/{trip_id}', 'RunningStatusController@show_status');

Route::get('/expected-schedule', 'SchedulePredictController@search');
Route::get('/expected-schedule/{trip_id}/{traveldate}', 'SchedulePredictController@show_status');

Route::post('/search-results-live', 'AjaxController@show_results_live');
Route::post('/search-results-prediction', 'AjaxController@show_results_prediction');
Route::patch('/search-suggestions', 'AjaxController@show_suggestions');
