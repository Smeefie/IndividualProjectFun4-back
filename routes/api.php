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

//=========================================================================================
//AUTHENTICATION
//=========================================================================================
Route::post('/Register', 'Api\AuthController@Register');
Route::post('/Login', 'Api\AuthController@Login');
Route::post('/Logout', 'Api\AuthController@Logout');

//=========================================================================================
//USERS
//=========================================================================================
Route::get('/GetAllUsers', 'Api\UserController@GetAllUsers');
Route::get('/GetUserById', 'Api\UserController@GetUserById');
Route::get('/GetUserByEmail', 'Api\UserController@GetUserByEmail');
Route::get('/GetAllUsersNotFriends', 'Api\UserController@GetAllUsersNotFriends');

//=========================================================================================
///FRIENDS
//=========================================================================================
//GETTERS
Route::get('/GetAllFriends', 'Api\UserController@GetAllFriends');
Route::get('/GetAllFriendRequests', 'Api\UserController@GetAllFriendRequests');
Route::get('/GetFriendRequest', 'Api\UserController@GetFriendRequest');
//SETTERS
Route::post('/AddFriend', 'Api\UserController@AddFriend');
Route::post('/AcceptFriend', 'Api\UserController@AcceptFriend');
//DELETE
Route::delete('/DeclineFriend', 'Api\UserController@DeclineFriend');
Route::delete('/RemoveFriend', 'Api\UserController@RemoveFriend');

//=========================================================================================
//GAME
//=========================================================================================
//GETTERS
Route::get('/GetAllGamePlayers', 'Api\GameController@GetAllGamePlayers');
Route::get('/GetGameInfo', 'Api\GameController@GetGameInfo');
Route::get('/GetGameStatus', 'Api\GameController@GetGameStatus');
Route::get('/GetGameById', 'Api\GameController@GetGameById');
Route::get('/GetGameByIdArray', 'Api\GameController@GetGameByIdArray');
Route::get('/GetAllGamePlayersForUser', 'Api\GameController@GetAllGamePlayersForUser');
Route::get('/CheckIfGameExists', 'Api\GameController@CheckIfGameExists');
//SETTERS
Route::post('/CreateGame', 'Api\GameController@CreateGame');
Route::post('/UpdateGame', 'Api\GameController@UpdateGame');
Route::post('/UpdateRounds', 'Api\GameController@UpdateRounds');
//DELETE
Route::delete('/DeleteGame', 'Api\GameController@DeleteGame');

//=========================================================================================
//MATCH HISTORY
//=========================================================================================
Route::get('/GetAllGamesByUserId', 'Api\HistoryController@GetAllGamesByUserId');
Route::get('/GetAllRoundsByGameId', 'Api\HistoryController@GetAllRoundsByGameId');
Route::get('/GetAllRoundPlayersByRoundId', 'Api\HistoryController@GetAllRoundPlayersByRoundId');
Route::get('/GetMatchDetails', 'Api\HistoryController@GetMatchDetails');

