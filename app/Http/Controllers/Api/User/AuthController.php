<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Models\User;

class AuthController extends Controller
{


    public function register(RegisterRequest $request) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)

        ]);
        if(! $user) 
            return response()->json(['status' => false, 'data' => 'some Thins Went Wrong'], 500);
        
        return response()->json([
            'status' => true,
            'data' => 'successfully Registered'
        ] , 201);

    } 

          
    public function login(LoginRequest $request) {

        $credentials = request(['email', 'password']);
        $token = auth('api')->attempt($credentials);
        
        if (!$token)
            return response()->json(['status' => false, 'data' => 'wrong Credentials'], 403);
        
        
        return response()->json([
            'status' => true,
            'data' => $token
        ] , 200);

    }   
     


    public function logout()
    {

        auth('api')->logout();
        return response()->json([
            'status' => true,
            'data' => 'successfully Logged Out'
        ] , 200);
    }


}
