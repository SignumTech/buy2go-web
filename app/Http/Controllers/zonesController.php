<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
class zonesController extends Controller
{
    public function addZones(Request $request){
        $this->validate($request, [
            "city" => "required",
            "subcity" => "required",
            "route" => "required"
        ]);

        $zone = new Zone;
        $zone->city = $request->city;
        $zone->subcity = $request->subcity;
        $zone->route = json_encode($request->route);
        $zone->save();

        return $zone;
    }

    public function updateZones(Request $request, $id){
        $this->validate($request, [
            "city" => "required",
            "subcity" => "required",
            "route" => "required"
        ]);

        $zone = Zone::find($id);
        $zone->city = $request->city;
        $zone->subcity = $request->subcity;
        $zone->route = json_encode($request->route);
        $zone->save();

        return $zone;
    }

    public function getZones(){
        $zones = Zone::paginate(10);
        return $zones;
    }
}
