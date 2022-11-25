<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('order_rejected.{orderId}', function ($user, $id) {
    return Auth::check();
    //return (int) $user->id === (int) $id;
});
Broadcast::channel('cash_withdrawn.{userId}', function ($user, $id) {
    return Auth::check();
    //return (int) $user->id === (int) $id;
});
Broadcast::channel('driver_assigned.{userId}', function ($user, $id) {
    return Auth::check();
    //return (int) $user->id === (int) $id;
});
Broadcast::channel('confirm_pickup.{orderId}', function ($user, $id) {
    return Auth::check();
    //return (int) $user->id === (int) $id;
});
Broadcast::channel('confirm_delivery.{orderId}', function ($user, $id) {
    return Auth::check();
    //return (int) $user->id === (int) $id;
});
Broadcast::channel('online.{id}', function ($user, $id=0) {
    if (Auth::check()) {
        return ['id' => $user->id, 'name' => $user->f_name];
    }
    //return (int) $user->id === (int) $id;
});
