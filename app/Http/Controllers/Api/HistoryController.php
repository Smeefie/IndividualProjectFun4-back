<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\User;
use App\Models\Round;
use App\Models\GamePlayer;
use App\Models\RoundPlayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function GetAllGamesByUserId(Request $request){
        $gameIdArray = GamePlayer::where('userId', '=', $request['userId'])->pluck('gameId')->toArray();
        $gameArray = array();
        foreach($gameIdArray as $gameId){
            array_push($gameArray, Game::where('gameId', '=', $gameId)->first());
        }
        return $gameArray;
    }

    public function GetAllRoundsByGameId($gameId){
        return Round::where('gameId', '=', $gameId)->get()->toArray();
    }
    
    public function GetAllRoundPlayersByRoundId($roundId){
        return RoundPlayer::where('roundId', '=', $roundId)->get()->toArray();
    }

    public function GetMatchDetails(Request $request){
        $rounds = Round::where('gameId', '=', $request['gameId'])->get()->toArray();
        $matchDetails = array();
        $roundNames = array();
        foreach($rounds as $round){
            $roundPlayers = RoundPlayer::where('roundId', '=', $round['id'])->get()->toArray();

            for($i = 0; $i < count($roundPlayers); $i++){
                $roundPlayers[$i] = array_merge($roundPlayers[$i], ['name' => User::where('id', '=', $roundPlayers[$i]['userId'])->first()['name']]);
            }
            
            array_push($matchDetails, ['roundInfo' => $round, 'playerInfo' => $roundPlayers]);
        };

        return $matchDetails;
    }
}
