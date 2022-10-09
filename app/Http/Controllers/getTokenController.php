<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class getTokenController extends Controller
{
    public function getUserToken(Request $request){
        $this->validate( $request, [
            "phone_no" => "required",
            "password" => "required|string"
        ]);
        $user = User::where('phone_no', $request->phone_no)->first();
        if(!$user){
            return response("Wrong credentials", 401);
        }
        if(!in_array($user->account_type, ["USER"])){
            return response("Access Denied", 401);
        }
        if (Hash::check($request->password, $user->password)) {
            if (Auth::check()){
                $request->user()->currentAccessToken()->delete();
            }
            $user_token = $user->createToken($user->f_name);

            return ['token' => $user_token->plainTextToken]; 
        }
        else{
            return response("Wrong credentials", 401);
        }
    }
}
