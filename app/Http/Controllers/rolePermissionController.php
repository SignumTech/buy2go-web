<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePermission;
class rolePermissionController extends Controller
{
    public function createRolePermission(Request $request){
        $this->validate($request, [
            "role" => "required",
            "permissions" => "required"
        ]);

        $rolePermission = new RolePermission;
        $rolePermission->role = $request->role;
        $rolePermission->permissions = json_encode($request->permissions);
        $rolePermission->save();

        return $rolePermission;
    }
    public function updateRolePermission(Request $request){

        $this->validate($request, [
            "role" => "required",
            "permissions" => "required"
        ]);

        $rolePermission = RolePermission::find($request->id);
        $rolePermission->role = $request->role;
        $rolePermission->permissions = json_encode($request->permissions);
        $rolePermission->save();

        return $rolePermission;
    }
    public function getRoles(){
        $roles = RolePermission::all();

        return $roles;
    }
    public function showRole($id){
        $role = RolePermission::find($id);

        return $role;
    }
    public function deleteRole($id){
        $role = RolePermission::find($id);
        $role->delete();
        return $role;
    }
}
