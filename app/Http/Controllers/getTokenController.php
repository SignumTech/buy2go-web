<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class getTokenController extends Controller
{
    public function getUserToken(Request $request){
        $this->validate( $request, [
            "phone_no" => "required",
            "password" => "required|string"
        ]);
        $user = User::where('phone_no', $this->formatPhoneNumber($request))->first();
        if(!$user){
            return response("Wrong credentials", 401);
        }
        if($user->shop_status == 'BANNED'){
            return response("Your account is banned!", 401);
        }
        if(!in_array($user->account_type, ["USER"])){
            return response("Access Denied", 401);
        }
        if (Hash::check($request->password, $user->password)) {
            if (Auth::check()){
                $request->user()->currentAccessToken()->delete();
            }
            $user_token = $user->createToken($user->f_name);

            return [
                'token' => $user_token->plainTextToken,
                'user' => $user
            ];
        }
        else{
            return response("Wrong credentials", 401);
        }
    }

    public function getDriverToken(Request $request){
        $this->validate( $request, [
            "phone_no" => "required",
            "password" => "required|string"
        ]);
        $user = User::where('phone_no', $this->formatPhoneNumber($request))->first();
        if(!$user){
            return response("Wrong credentials", 401);
        }
        if(!in_array($user->user_role, ["DRIVER"])){
            return response("Access Denied", 401);
        }
        if (Hash::check($request->password, $user->password)) {
            if (Auth::check()){
                $request->user()->currentAccessToken()->delete();
            }
            $user_token = $user->createToken($user->f_name);

            return [
                'token' => $user_token->plainTextToken,
                'user' => $user
            ];
        }
        else{
            return response("Wrong credentials", 401);
        }
    }

    public function getAgentToken(Request $request){
        $this->validate( $request, [
            "phone_no" => "required",
            "password" => "required|string"
        ]);
        $user = User::where('phone_no', $this->formatPhoneNumber($request))->first();
        if(!$user){
            return response("Wrong credentials", 401);
        }
        if(!in_array($user->user_role, ["AGENT"])){
            return response("Access Denied", 401);
        }
        if (Hash::check($request->password, $user->password)) {
            if (Auth::check()){
                $request->user()->currentAccessToken()->delete();
            }
            $user_token = $user->createToken($user->f_name);

            return [
                'token' => $user_token->plainTextToken,
                'user' => $user
            ];
        }
        else{
            return response("Wrong credentials", 401);
        }
    }

    public function getWarehouseToken(Request $request){
        $this->validate( $request, [
            "phone_no" => "required",
            "password" => "required|string"
        ]);
        $user = User::where('phone_no', $this->formatPhoneNumber($request))->first();
        if(!$user){
            return response("Wrong credentials", 401);
        }
        if(!in_array($user->user_role, ["WAREHOUSE_MANAGER"])){
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

    public function formatPhoneNumber(Request $request){
        $this->validate($request, [
            "country_code" => "required",
            "phone_no" => "required"
        ]);
        if(strlen($request->phone_no) == 10){
            $request->phone_no = substr($request->phone_no, 1);
        }
        else{
            $request->phone_no = $request->phone_no;
        }
        return $request->phone_no;
    }

}
