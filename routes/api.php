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

//AUTHENTICATION
Route::post('/Register', 'Api\AuthController@Register');
Route::post('/Login', 'Api\AuthController@Login');
Route::get('/Logout', 'Api\AuthController@Logout');

//GAME
Route::get('GetAllUsers', 'Api\GameController@GetAllUsers');
Route::post('StartGame', 'Api\GameController@StartGame');
