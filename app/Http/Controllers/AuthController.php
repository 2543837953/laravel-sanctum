<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //用于注册用户
    public function register(Request $request){
        $user =  User::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password')),
        ]);
        return \response([
           'msg'=>'success',
           'data'=>$user
        ],200);
    }
    //用于登录
    public function login(Request $request){
        if (!Auth::attempt($request->only(['email','password']))){
            return response(["msg"=>"Bad Credential"],Response::HTTP_UNAUTHORIZED);
        }
        //创建token,这个token会被sanctum存入数据库表 personal_access_tokens

        $token=Auth::user()->createToken($request->get('email'))->plainTextToken;
        $cookie=cookie('token',$token,60*24); //用cookie带到客户端
        return response(['token'=>$token])->withCookie($cookie);
    }
    //用于获取当前登录用户
    public function user(Request $request){
        return Auth::user();
    }
    //用于登出
    public function logout(Request $request){
        $cookie=\Illuminate\Support\Facades\Cookie::forget('token');
        return response(['msg'=>'success'])->withCookie($cookie);
//      如果不再使用当前token，可用以下语句删除:
//      Auth::user()->currentAccessToken()->delete();
//      return response(['msg'=>'success']);
    }
}
