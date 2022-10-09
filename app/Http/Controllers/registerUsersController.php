<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class registerUsersController extends Controller
{
    public function registerUser(Request $request){
        
        $this->validate($request, [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            //'preference' => ['required', 'string', 'max:255'],
            'phone_no' => ['required','regex:/(01)[0-9]{9}/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = new User;
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        $user->phone_no = $request->phone_no;
        $user->password = Hash::make($request->password);
        $user->account_type = "USER";
        $user->user_role = "USER";
        //$user->otp = rand(1000 , 9999);
        $user->save();

        $user_token = $user->createToken($user->f_name);

        return ['token' => $user_token->plainTextToken];
    }
}
