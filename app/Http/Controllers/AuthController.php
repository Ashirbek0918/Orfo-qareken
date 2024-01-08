<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ResponseController;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::Where('phone', $request->phone)->first();
        $password = $request->password;
        // return $user;
        if(!$user OR Hash::check($password, $user->password)){
            return ResponseController::error('Invalid password or email',401);
        }
        $token = $user->createToken('user')->plainTextToken;
        return ResponseController::data([
            'token' => $token,
        ]);
    }    
    public function getme(Request $request){
        $user = auth()->user();
        return $user;
    }
    public function logOut(Request $request){
        $request->user()->currentAccessToken()->delete();
        return ResponseController::success('You have successfully logged out',200);
    }
}
