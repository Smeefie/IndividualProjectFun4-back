<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    //Get all users for game
    public function GetAllUsers(){
        $users  = User::where('id', '!=', auth()->id())->get();

        return $users
            ->makeHidden('email')
            ->makeHidden('email_verified_at')
            ->makeHidden('created_at')
            ->makeHidden('updated_at')
            ->toArray();
    }

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
