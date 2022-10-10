<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class staffController extends Controller
{
    public function getStaff(){
        $staff = User::where('account_type', 'Staff')->orderBy('users.f_name', 'ASC')->get();

        return $staff;
    }

    public function registerStaff(Request $request){
        $this->validate($request, [
            'f_name' => "required | string",
            'l_name' => "required | string",
            'title' => "required | string",
            'phone_no' => "required|integer|digits:9",
            'user_role' => "required | string",
            'email' => "required | email | unique:users",
        ]);

        $staff = new User;
        $staff->f_name = $request->f_name;
        $staff->l_name = $request->l_name;
        $staff->title = $request->title;
        $staff->phone_no = $request->phone_no;
        $staff->email = $request->email;
        $staff->user_role = $request->user_role;
        $staff->otp = rand(1000 , 9999);
        $staff->account_type = 'Staff';

        $staff->save();
        return $staff;
    }

    public function editStaff(Request $request){
        
        $this->validate($request, [
            'f_name' => "required | string",
            'l_name' => "required | string",
            'title' => "required | string",
            'phone_no' => "required|integer|digits:9",
            'user_role' => "required | string",
            'email' => "required | email ",
        ]);

        $staff = User::find($request->id);

        $staff->f_name = $request->f_name;
        $staff->l_name = $request->l_name;
        $staff->title = $request->title;
        $staff->phone_no = $request->phone_no;
        $staff->email = $request->email;
        $staff->user_role = $request->user_role;
        $staff->account_type = 'Staff';

        $staff->save();
        return $staff;

    }

    public function showStaff($id){
        $staff = User::find($id);
        return $staff;
    }

    public function resetStaffPass(Request $request){
        $this->validate( $request, [
            "password" => "required",
            "user_id" => "required | integer"
        ]);

        $staff = User::find($request->user_id);
        $staff->password = Hash::make($request->password);
        $staff->save();

        return $staff;
    }

    public function searchStaff(Request $request){
        $this->validate( $request, [
            "queryItem" => "required"
        ]);

        $staff = User::where("phone_no", $request->queryItem)
                     ->orWhere("f_name", $request->queryItem)
                     ->orderBy('users.f_name', 'ASC')
                     ->get();
            
        return $staff;
    }

    public function getStaffPermissions(){
        $user_id = auth()->user()->id;
        $permissions = User::join('role_permissions', 'users.user_role', '=', 'role_permissions.role')
                           ->where('users.id', $user_id)
                           ->select('role_permissions.permissions')
                           ->first();
        return $permissions;
    }

    public function savePassword(Request $request){

        $this->validate( $request, [
            "oldPassword" => "required",
            "newPassword" => "required",
            "confirmPassword" => "required"
        ]);
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        if(password_verify($request->oldPassword, $user->password)){
            
            if( $request->newPassword == $request->confirmPassword ){
                $user->password = Hash::make($request->newPassword);
                $user->save();
                return $user;
            }
            else{
                return response('NC', 422);
            }
        }
        else{
            return response('OP', 422);
        }


    }
    public function deleteStaff($id){
        $user = User::find($id);
        $user->delete();
        return $user;
    }
}
