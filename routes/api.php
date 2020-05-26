<?php

use App\Models\User;
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

//USERS
Route::post('/GetAllUsers', 'Api\UserController@GetAllUsers');
Route::post('/GetUserById', 'Api\UserController@GetUserById');
Route::post('/GetUserByEmail', 'Api\UserController@GetUserByEmail');

//FRIENDS
Route::post('/AddFriend', 'Api\UserController@AddFriend');
Route::post('/AcceptFriend', 'Api\UserController@AcceptFriend');
Route::post('/DeclineFriend', 'Api\UserController@DeclineFriend');
Route::post('/RemoveFriend', 'Api\UserController@RemoveFriend');
Route::post('/GetAllUsersNotFriends', 'Api\UserController@GetAllUsersNotFriends');
Route::post('/GetAllFriends', 'Api\UserController@GetAllFriends');
Route::post('/GetAllFriendRequests', 'Api\UserController@GetAllFriendRequests');
Route::post('/GetFriendRequest', 'Api\UserController@GetFriendRequest');

//GAME
Route::post('/CreateGame', 'Api\GameController@CreateGame');
Route::post('/GetAllGamePlayers', 'Api\GameController@GetAllGamePlayers');
Route::post('/GetGameInfo', 'Api\GameController@GetGameInfo');
Route::post('/CheckIfGameExists', 'Api\GameController@CheckIfGameExists');
Route::post('/GetGameStatus', 'Api\GameController@GetGameStatus');
Route::post('/UpdateGame', 'Api\GameController@UpdateGame');
Route::post('/DeleteGame', 'Api\GameController@DeleteGame');
Route::post('/GetGameById', 'Api\GameController@GetGameById');
Route::post('/GetAllGamePlayersForUser', 'Api\GameController@GetAllGamePlayersForUser');

