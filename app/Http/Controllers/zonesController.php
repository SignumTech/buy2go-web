<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
class zonesController extends Controller
{
    public function addZones(Request $request){
        $this->validate($request, [
            "zone_name" => "required",
            "sub_city_id" => "required",
            "route" => "required"
        ]);

        $zone = new Zone;
        $zone->zone_name = $request->zone_name;
        $zone->sub_city_id = $request->sub_city_id;
        $zone->route = json_encode($request->route);
        $zone->save();

        return $zone;
    }

    public function updateZones(Request $request, $id){
        $this->validate($request, [
            "zone_name" => "required",
            "sub_city_id" => "required",
            "route" => "required"
        ]);

        $zone = Zone::find($id);
        $zone->zone_name = $request->zone_name;
        $zone->sub_city_id = $request->sub_city_id;
        $zone->route = json_encode($request->route);
        $zone->save();

        return $zone;
    }

    public function getZones(){
        $zones = Zone::join('sub_cities', 'sub_cities.id', '=', 'zones.sub_city_id')
                     ->join('cities', 'sub_cities.city_id', '=', 'cities.id')
                     ->join('countries', 'countries.id', 'cities.country_id')
                     ->select('zones.route','zones.zone_name', 'zones.sub_city_id', 'zones.id', 'cities.city_name', 'sub_cities.sub_city_name', 'countries.country_name')
                     ->paginate(10);
        return $zones;
    }
}
