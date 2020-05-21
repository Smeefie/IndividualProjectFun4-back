<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    //Initialize the game
    public function StartGame(Request $request){

        $userArray = array();
        $index = 0;

        //dd($request[]);

        foreach($request->toArray() as $user){
            $tempInput = array();
            $tempInput['id'] = $user['id'];
            $tempInput['name'] = $user['name'];

            $userArray[$index] = $tempInput;
            
            $index ++;
        }

        return $userArray;
    }

    public function StopGame(Request $request){

    }
}
