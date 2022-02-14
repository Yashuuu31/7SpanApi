<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $validate = Validator::make($request->all(),[
            'email' => "required|email",
            'password' => "required|min:8",
        ]);

        if($validate->fails()){
            $error = $validate->errors()->first();
            return $this->jsonError($error);
        }

        $checkData = auth()->attempt(['email' => $request->email, 'password' => $request->password]);
        if($checkData){
            $user = $request->user();
            if($user->is_active == 0){
                return $this->jsonError("User Inactivate, Please Contact Admin.");
            }

            $tokenResult = $user->createToken('Personal Access Token');
            return response()->json([
                'success' => 1,
                'msg' => "User Login Successfully.",
                'access_token' => $tokenResult->accessToken,
                'token_type' => "Bearer",
                'result' => $user,
            ]);
        }
    }
}
