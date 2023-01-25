<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\AuthPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //ユーザー登録
    public function register(AuthPost $request){

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user -> createToken('auth_token') -> plainTextToken;

        return response() -> json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    //ログイン
    public function login(AuthPost $request){
              
        $user = User::where('email', $request->email)->firstOrFail();
            
        $token = $user->createToken('auth_token')->plainTextToken;
            
        return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
        ]);
    }

    //ログアウト
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

}
