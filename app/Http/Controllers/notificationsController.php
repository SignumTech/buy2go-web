<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class notificationsController extends Controller
{
    public function getMyNotifications(){
        
        $user = User::find(auth()->user()->id);
        return $user->unreadNotifications;
    }

    public function markAsRead($id){
        
    }

    public function markAllAsRead(){
        $user = User::find(auth()->user()->id);
        $user->unreadNotifications()->update(['read_at' => now()]);
    }
}
