<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Balance;
use Illuminate\Support\Facades\Hash;
class registerUsersController extends Controller
{
    public function registerUser(Request $request){
        
        $this->validate($request, [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            //'preference' => ['required', 'string', 'max:255'],
            //'phone_no' => ['required','regex:/(01)[0-9]{9}/'],
            'phone_no' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country_code' => ['required']
        ]);

        $user = new User;
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->country_code = $request->country_code;
        $user->phone_no = $request->phone_no;
        $user->password = Hash::make($request->password);
        $user->account_type = "USER";
        $user->user_role = "USER";
        //$user->otp = rand(1000 , 9999);
        $user->save();

        $user_token = $user->createToken($user->f_name);

        return [
            'token' => $user_token->plainTextToken,
            'user' => User::find($user->id)
        ];
    }

    public function registerAgent(Request $request){
        
        $this->validate($request, [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            //'preference' => ['required', 'string', 'max:255'],
            //'phone_no' => ['required','regex:/(01)[0-9]{9}/'],
            'phone_no' => ['required', 'digits:10', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User;
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->country_code = $request->country_code;
        $user->phone_no = $request->phone_no;
        $user->password = Hash::make($request->password);
        $user->account_type = "AGENT";
        $user->user_role = "AGENT";
        //$user->otp = rand(1000 , 9999);
        $user->save();

        $balance = new Balance;
        $balance->balance = 0;
        $balance->user_id = $user->id;
        $balance->save();

        $user_token = $user->createToken($user->f_name);

        return [
            'token' => $user_token->plainTextToken,
            'user' => User::find($user->id)
        ];
    }

    public function resetPassword(Request $request){
        $this->validate($request, [
            'oldPassword' => ['required', 'string', 'min:8'],
            'newPassword' => ['required', 'string', 'min:8' , 'confirmed']
        ]);

        $user = User::find(auth()->user()->id);
        if(!Hash::check($request->oldPassword, $user->password)){
            return response("Wrong old password", 422);
        }
        $user->password = Hash::make($request->newPassword);
        $user->save();

        return $user;
    }

    public function forgetPassword(Request $request){
        $this->validate($request, [
            'phone_no' => ['required'],
            'password' => ['required', 'string', 'min:8']
        ]);

        $user = User::where('phone_no', $request->phone_no)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $user_token = $user->createToken($user->f_name);
        return [
            'token' => $user_token->plainTextToken,
            'user' => User::find($user->id)
        ];

    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            "f_name" => "required",
            "l_name" => "required",
        ]);
        $user = user::find(auth()->user()->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->save();

        return $user;
    }

    public function checkPhoneNumber(Request $request){
        $this->validate($request, [
            "country_code" => "required",
            "phone_no" => "required"
        ]);
        
                
        if(strlen($request->phone_no) == 10){
            $request->phone_no = substr($request->phone_no, 1);
        }
        $phone_no = User::where('phone_no', $request->phone_no)->first();
        if($phone_no){
            return response('Phone already exists', 422);
        }

        return $request->phone_no;
    }

    public function checkPhoneForget(Request $request){
        $this->validate($request, [
            "country_code" => "required",
            "phone_no" => "required"
        ]);
        
                
        if(strlen($request->phone_no) == 10){
            $request->phone_no = substr($request->phone_no, 1);
        }
        $phone_no = User::where('phone_no', $request->phone_no)->first();
        if($phone_no){
            return $request->phone_no;
        }
        return response('Phone already exists', 422);

        
    }
    
}
