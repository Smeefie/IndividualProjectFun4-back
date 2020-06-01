<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\Round;
use App\Models\RoundPlayer;
use Illuminate\Http\Request;

class GameController extends Controller
{
    //Initialize the game
    public function CreateGame(Request $request)
    {

        $gameId = mt_rand(10000000, 99999999);
        $gamePlayerArray = array();

        $game = Game::create([
            'gameId' => $gameId,
            'creatorId' => $request['creatorId'],
            'limit' => $request['limit'],
        ]);

        if (!$game) {
            return response('Failed', 401);
        }

        foreach ($request['players'] as $id) {
            $gamePlayer = GamePlayer::create([
                'gameId' => $gameId,
                'userId' => $id,
            ]);

            if ($gamePlayer) {
                array_push($gamePlayerArray, $gamePlayer);
            } else {
                return response('Failed', 401);
            }
        }

        return response($gameId, 200);
    }

    public function CheckIfGameExists(Request $request)
    {
        return Game::where('gameId', '=', $request['gameId'])->exists() ? true : false;
    }

    public function GetGameStatus(Request $request)
    {
        return Game::where('gameId', '=', $request['gameId'])->first()['status'];
    }

    public function GetAllGamePlayers(Request $request)
    {
        return GamePlayer::where('gameId', '=', $request['gameId'])->get()->toArray();
    }

    public function GetGameInfo(Request $request)
    {
        $playerRequest = new Request();
        $gamePlayers = $this->GetAllGamePlayers($playerRequest->replace(['gameId' => $request['gameId']]));

        $gameInfo = Game::where('gameId', '=', $request['gameId'])->first();
        return ['players' => $gamePlayers, 'info' => $gameInfo];
    }

    public function UpdateGame(Request $request)
    {
        $game = Game::where('gameId', '=', $request['gameId'])->first();
        $game->update([
            'status' => $request['status'],
            'round' => $request['round'],
        ]);

        foreach ($request['players'] as $player) {
            GamePlayer::where('gameId', '=', $request['gameId'])
                ->where('userId', '=', $player['id'])
                ->first()
                ->update([
                    'score' => $player['score'],
                    'status' => $player['status'],
                    'timesKnocked' => $player['timesKnocked'],
                    'roundsWon' => $player['roundsWon'],
                    'roundsWonWithJack' => $player['roundsWonWithJack'],
                ]);
        }
    }

    public function UpdateRounds(Request $request)
    {
        $roundsObject = $request['roundObject'];
        $roundInfo = $roundsObject['roundInfo'];
        $playerInfo = $roundsObject['playerInfo'];
        $gameLimit = Game::where('gameId', '=', $request['gameId'])->first()['limit'];
        $loserCount = 0;

        if (Round::where('gameId', '=', $request['gameId'])->where('roundNr', '=', $roundInfo['roundNr'])->exists()) {
            return response('input exists', 401);
        }

        $round = Round::create([
            'gameId' => $request['gameId'],
            'roundNr' => $roundInfo['roundNr'],
        ]);
        if ($round) {
            for ($i = 0; $i < count($playerInfo); $i++) {
                $forPlayer = $playerInfo[$i];
                if ($request['winner'] != $forPlayer['id'] && $forPlayer['status'] == 0) {
                    $playerInfo[$i]['score'] = $forPlayer['score'] + (1 + $request['withJack'] + $playerInfo[$i]['knocked']);
                    $playerInfo[$i]['status'] = $playerInfo[$i]['score'] >= $gameLimit ? 1 : 0;
                }
            }

            for ($i = 0; $i < count($playerInfo); $i++) {
                $forPlayer = $playerInfo[$i];
                if ($forPlayer['status'] == 1) {
                    $loserCount++;
                }
            }

            if ($loserCount == count($playerInfo) - 1) {
                for ($i = 0; $i < count($playerInfo); $i++) {
                    $forPlayer = $playerInfo[$i];
                    if ($forPlayer['status'] == 0) {
                        $playerInfo[$i]['status'] = 2;
                        break;
                    }
                }
            }

            foreach ($playerInfo as $roundPlayer) {
                $gamePlayer = RoundPlayer::create([
                    'roundId' => Round::where('gameId', '=', $request['gameId'])
                        ->where('roundNr', '=', $roundInfo['roundNr'])
                        ->first()['id'],
                    'userId' => $roundPlayer['id'],
                    'score' => $roundPlayer['score'],
                    'withJack' => ($request['winner'] == $roundPlayer['id']) && $request['withJack'] ? 1 : 0,
                    'timesKnocked' => $roundPlayer['knocked'],
                    'status' => $roundPlayer['status'],
                    'roundStatus' => $request['winner'] == $roundPlayer['id'] ? 1 : 0,
                ]);
                if (!$gamePlayer) {
                    return response('Failed', 401);
                }
            }
        } else {
            return response('Failed', 401);
        }

        return response($playerInfo);
    }

    public function DeleteGame($gameId)
    {
        $game = Game::where('gameId', '=', $gameId)->first();
        if ($game) {
            $game->delete();
        }

        $players = GamePlayer::where('gameId', '=', $gameId)->get();
        foreach ($players as $player) {
            $player->delete();
        }

        $rounds = Round::where('gameId', '=', $gameId)->get();
        foreach ($rounds as $round) {
            $roundPlayers = RoundPlayer::where('roundId', '=', $round['id'])->get();
            foreach ($roundPlayers as $roundPlayer) {
                $roundPlayer->delete();
            }

            $round->delete();
        }

    }

    public function GetGameById(Request $request)
    {
        return Game::where('gameId', '=', $request['gameId'])->first();
    }

    public function GetGameByIdArray(Request $request)
    {
        $gameArray = array();
        foreach ($request['gameIdArray'] as $gameId) {
            array_push($gameArray, Game::where('gameId', '=', $gameId)->first());
        }

        return $gameArray;
    }

    public function GetAllGamePlayersForUser(Request $request)
    {
        return GamePlayer::where('userId', '=', $request['userId'])->get()->toArray();
    }
}
