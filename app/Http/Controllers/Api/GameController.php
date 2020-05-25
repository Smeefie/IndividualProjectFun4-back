<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\User;
use App\Models\GamePlayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    //Initialize the game
    public function CreateGame(Request $request){

        $gameId = mt_rand(10000000, 99999999);
        $gamePlayerArray = array();

        $game = Game::create([
            'gameId' => $gameId,
            'limit' => $request['limit'],
        ]);

        if(!$game) return response('Failed', 401);

        foreach($request['players'] as $id){
            $gamePlayer = GamePlayer::create([
                'gameId' => $gameId,
                'userId' => $id,
            ]);

            if($gamePlayer){
                array_push($gamePlayerArray, $gamePlayer);
            }else{
                return response('Failed', 401);
            }
        }

        return response($gameId, 200);
    }

    public function CheckIfGameExists(Request $request){
        return Game::where('gameId', '=', $request['gameId'])->exists() ? 1 : 0;
    }

    public function GetGameStatus(Request $request){
        return Game::where('gameId', '=', $request['gameId'])->first()['status'];
    }

    public function GetAllGamePlayers(Request $request){
        return GamePlayer::where('gameId',  '=', $request['gameId'])->get()->toArray();
    }

    public function GetGameInfo(Request $request){
        $playerRequest = new Request();
        $gamePlayers = $this->GetAllGamePlayers($playerRequest->replace(['gameId' => $request['gameId']]));
        
        $gameInfo = Game::where('gameId', '=', $request['gameId'])->first();
        return ['players' => $gamePlayers, 'info' => $gameInfo];
    }

    public function UpdateGame(Request $request){
        $game = Game::where('gameId', '=', $request['gameId'])->first();
        $game->update([
            'status' => $request['status'],
            'round' => $request['round']
        ]);

        foreach($request['players'] as $player){
            $gamePlayer = GamePlayer::where('gameId', '=', $request['gameId'])
                ->where('userId', '=', $player['id'])
                ->first();

            if($gamePlayer){
                $gamePlayer->update([
                    'score' => $player['score'],
                    'status' => $player['status']
                ]);
            }else{
                return response('Failed', 401);
            }
        }
    }
}
