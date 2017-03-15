<?php

use Illuminate\Http\Request;

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

Route::get('/info', function () {
    $data = ["info" => "Testing route for Postman"];
    return response()->json($data,200);
});

Route::post('/request', function (Request $request) {
    $data = $request->all();
    return response()->json($data,200);
});

Route::resource('/customer', 'CustomerController');
Route::get('/customers', 'CustomerController@index');
