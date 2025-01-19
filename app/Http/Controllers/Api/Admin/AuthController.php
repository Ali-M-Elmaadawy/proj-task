<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\LoginRequest;
use App\Models\Admin;

class AuthController extends Controller
{
       
    public function login(LoginRequest $request) {

        $credentials = request(['username', 'password']);
        $token = auth('admin')->attempt($credentials);
        
        if (!$token)
            return response()->json(['status' => false, 'data' => 'wrong Credentials'], 403);
        
        
        return response()->json([
            'status' => true,
            'data' => $token
        ] , 200);

    }   
     


    public function logout()
    {

        auth('admin')->logout();
        return response()->json([
            'status' => true,
            'data' => 'successfully Logged Out'
        ] , 200);
    }


}
