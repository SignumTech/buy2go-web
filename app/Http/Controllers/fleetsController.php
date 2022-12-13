<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\DriverLocation;
class fleetsController extends Controller
{
    public function broadcastDriverLocation(Request $request){
        $this->validate($request, [
            "lat" => "required",
            "lng" => "required",
            "assignments" => "required"
        ]);

        broadcast(new DriverLocation((double)$request->lat, (double)$request->lng, $request->assignments, auth()->user()->id, auth()->user()->f_name.' '.auth()->user()->l_name))->toOthers();
        
    }
}
