<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\AuthPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;


class AuthController extends Controller
{
    //ユーザー登録
    public function register(AuthPost $request){

        $user_email = User::where('email',$request->email)->first();

        if($user_email == null ){

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

        }else{
            $response = response()->json([
                'email' => ['このメールアドレスは既に登録されています']
            ],400);

            throw new HttpResponseException($response);

        };
    }

    //ログイン
    public function login(AuthPost $request){
              
        $password = $request->password;

        $user = User::where('email', $request->email)
            ->firstOr(function(){
                $response = response()->json([
                    'email' => ['未登録のメールアドレスです']
                ],400);
    
                throw new HttpResponseException($response);
            });
        
        if(Hash::check($password, $user->password)){
            $token = $user->createToken('auth_token')->plainTextToken;
            $user_id = $user->id;
            $user_name = $user->name;
            
            return response()->json([
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'user_id' => $user_id,
                        'user_name' => $user_name
            ]);

        }else{
            $response = response()->json([
                'password' => ['パスワードが一致しません']
            ],400);

            throw new HttpResponseException($response);
        };
    }

    //ログアウト
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

}
