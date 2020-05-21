<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function GetAllUsers(){
        return User::where('id', '!=', auth()->id())
            ->get();
            ->toArray();            
    }

    public function GetUserById(Request $request){
        return User::where('id', '=', $request['id'])
            ->get()
            ->toArray();                    
    }

    public function GetUserByEmail(Request $request){
        return User::where('email', '=', $request['email'])
            ->get()
            ->toArray();      
    }
}
