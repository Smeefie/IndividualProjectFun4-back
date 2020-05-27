<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //GETTING USERS
    public function GetAllUsers(){
        return User::where('id', '!=', auth()->id())
            ->get()
            ->toArray();            
    }

    public function GetUserById($userId){
        return User::where('id', '=', $userId)
            ->first()
            ->toArray();                    
    }

    public function GetUserByEmail($email){
        return User::where('email', '=', $email)
            ->first()
            ->toArray();      
    }

    public function GetAllUsersNotFriends(Request $request){

        $friendships = Friendship::where('requester', '=', $request['id'])->get()->toArray();
        $friendIdArray = array();
        $users= User::where('id', '!=', $request['id'])->get()->toArray();
        $userIdArray = array();
        
        foreach($friendships as $id){
            array_push($friendIdArray, $id['userRequested']);
        }

        foreach($users as $id){
            array_push($userIdArray, $id['id']);
        }

        $users = array();
        foreach(array_values(array_diff($userIdArray, $friendIdArray)) as $id){
            array_push($users, User::where('id', '=', $id)->first());
        }

        return $users;
    }

    public function GetAllFriends(Request $request){

        $friendships = Friendship::where('requester', '=', $request['id'])->get()->toArray();
        $friendArray = array();
        
        foreach($friendships as $id){
            array_push($friendArray, User::where('id', '=', $id['userRequested'])->first());
        }
        return $friendArray;
    }

    public function GetFriendRequest(Request $request){
        return Friendship::where('requester', '=', $request['id'])
                ->where('userRequested', '=',  $request['friendId'])
                ->first()
                ->toArray();
    }

    public function AddFriend(Request $request){
        if (!Friendship::where('requester', '=', $request['id'])
            ->where('userRequested', '=',  $request['friendId'])
            ->exists()) {


            if (!Friendship::where('userRequested', '=', $request['id'])
                ->where('requester', '=',  $request['friendId'])
                ->exists()) {

                $friendship = Friendship::create([
                    'requester' => $request['id'],
                    'userRequested' => $request['friendId']
                ]);
            
                if($friendship){
                    return response()->json($friendship, 200);
                };    
            }  
            else{
                $friendship = Friendship::create([
                    'requester' => $request['id'],
                    'userRequested' => $request['friendId'],
                    'status' => 1
                ]);

                $friendshipReverse = Friendship::where('userRequested', '=', $request['id'])
                                ->where('requester', '=',  $request['friendId'])
                                ->first()
                                ->update([
                                    'status' => 1
                                ]);

                return response()->json($friendship, 200);
            }           
         }

         return response()->json('Already Added', 501);         
    }

    public function AcceptFriend(Request $request){
        $friendship = Friendship::where('userRequested', $request['id'])
            ->where('requester', $request['friendId'])
            ->first();

        if($friendship){
            $friendship->update([
                'status' => 1
            ]);

            if($friendship){
                if (!Friendship::where('requester', '=', $request['id'])
                    ->where('userRequested', '=',  $request['friendId'])
                    ->exists()) {
                
                    $friendship = Friendship::create([
                        'requester' => $request['id'],
                        'userRequested' => $request['friendId'],
                        'status' => 1
                    ]); 
                }
                return response()->json($friendship, 200);
             };
        }      
        
        return response()->json('Failed', 501);
    }

    public function DeclineFriend(Request $request){
        $friendship = Friendship::where('userRequested', $request['id'])
            ->where('requester', $request['friendId'])
            ->first();

        if($friendship){
            $friendship->delete();
        }
    }

    public function RemoveFriend(Request $request){
        $friendship = Friendship::where('requester', '=', $request['id'])
            ->where('userRequested', '=',  $request['friendId'])
            ->first();

        $friendshipReverse = Friendship::where('userRequested', '=', $request['id'])
            ->where('requester', '=',  $request['friendId'])
            ->first();

        if($friendship){
            $friendship->delete();

            if($friendshipReverse){
                $friendshipReverse->delete();
            } 

            if($friendship) return response()->json($friendship, 200);
        } 

        return response()->json('Failed', 501);
    }

    public function GetAllFriendRequests(Request $request){
        $friendship =  Friendship::where('userRequested', '=', $request['id'])
            ->where('status', '=', 0)
            ->get()
            ->toArray();    
            
        return response($friendship, 200);
    }
}
