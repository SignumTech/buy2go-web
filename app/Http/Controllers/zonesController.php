<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use App\Exports\ZonesExport;
use Maatwebsite\Excel\Facades\Excel;
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

    public function filterZones(Request $request){
        $zones = $this->filterData($request)->paginate(10);
        return $zones;
    }

    public function exportZones(Request $request){
        $zones = $this->filterData($request)->select(
            'zones.id',
            'zone_name',
            'sub_city_name',
            'city_name',
            'country_name',
            'route'
        )->get();
        return Excel::download(new ZonesExport($zones), 'zones.xlsx');
    }

    public function filterData(Request $request){
        return Zone::join('sub_cities', 'sub_cities.id', '=', 'zones.sub_city_id')
                    ->join('cities', 'sub_cities.city_id', '=', 'cities.id')
                    ->join('countries', 'countries.id', 'cities.country_id')
                    ->when($request->zone_name !=null, function ($q) use($request){
                        return $q->where('zone_name', 'LIKE', '%'.$request->zone_name.'%');
                    })
                    ->when($request->sub_city_id !=null, function ($q) use($request){
                        return $q->where('sub_city_id', 'LIKE', '%'.$request->sub_city_id.'%');
                    })
                    ->when($request->city_id !=null, function ($q) use($request){
                        return $q->where('city_id', 'LIKE', '%'.$request->city_id.'%');
                    })
                    ->when($request->country_id !=null, function ($q) use($request){
                        return $q->where('country_id', 'LIKE', '%'.$request->country_id.'%');
                    });
    }

    public function deleteZone($id){
        if(auth()->user()->user_role == 'ADMIN'){
            $zone = Zone::find($id);
            $zone->delete();

            return $zone;
        }
        else{
            return response('Unauthorized', 401);
        }
    }
}
