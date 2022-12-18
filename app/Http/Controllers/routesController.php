<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZoneRoute;
use App\Models\DriverDetail;
use App\Models\Zone;
use App\Models\AddressBook;
use App\Exports\routeExport;
use Maatwebsite\Excel\Facades\Excel;
class routesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = ZoneRoute::paginate(10);
        foreach($routes as $route){
            $zone = Zone::find($route->zone_id);
            $route->zone_name = ($zone)?$zone->zone_name:null;
            $route->drivers_count = DriverDetail::where('route_id', $route->id)->count();
            $route->shops_count = AddressBook::where('route_id', $route->id)->count();
        }
        return $routes;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "route_name" => "required",
            "zone_id" => "required",
            "selectedShops" => "required"
        ]);
        //dd($request->selectedShops);
        $route = new ZoneRoute;
        $route->route_name = $request->route_name;
        $route->zone_id = $request->zone_id;
        $route->save();
        
        foreach($request->selectedShops as $shop){
            $address = AddressBook::find($shop);
            $address->route_id = $route->id;
            $address->save();
        }

        return $route;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $route = ZoneRoute::find($id);
        return $route;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "route_name" => "required",
            "zone_id" => "required",
            "selectedShops" => "required"
        ]);

        $route = ZoneRoute::find($id);
        $route->route_name = $request->route_name;
        $route->zone_id = $request->zone_id;
        $route->save();

        $shops = AddressBook::where('route_id', $route->id)->update(['route_id'=> null]);
        foreach($request->selectedShops as $shop){
            $address = AddressBook::find($shop);
            $address->route_id = $route->id;
            $address->save();
        }

        return $route;
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filterRoutes(Request $request){
        
        $routes = $this->filterData($request)->paginate(10);
        foreach($routes as $route){
            $route->drivers_count = DriverDetail::where('route_id', $route->id)->count();
            $route->shops_count = AddressBook::where('route_id', $route->id)->count();
        }
        return $routes;
    }

    public function exportRoutes(Request $request){
        $routes = $this->filterData($request)->get();
        foreach($routes as $route){
            $route->drivers_count = DriverDetail::where('route_id', $route->id)->count();
            $route->shops_count = AddressBook::where('route_id', $route->id)->count();
        }
        return Excel::download(new routeExport($routes), 'routes.xlsx');
    }

    public function filterData(Request $request){
        $routes = ZoneRoute::join('zones', 'zone_routes.zone_id', '=', 'zones.id')
                            ->when($request->route_name !=null, function ($q) use($request){
                                return $q->where('route_name', 'LIKE', '%'.$request->route_name.'%');
                            })
                            ->when($request->zone_id !=null, function ($q) use($request){
                                return $q->where('zone_routes.zone_id', $request->zone_id);
                            })
                           ->select('zone_routes.id', 'zone_routes.route_name', 'zones.zone_name');
        
        return $routes;
    }

    public function getRoute($id){
        $route = ZoneRoute::find($id);
        $route->selectedShops = AddressBook::where('route_id', $id)->pluck('id');
        $route->addresses = AddressBook::where('route_id', $id)->get();
        return $route;
    }

    public function getRoutesRaw(){
        $routes = ZoneRoute::join('zones', 'zone_routes.zone_id', '=', 'zones.id')
                            ->select('zone_routes.id', 'zone_routes.route_name', 'zones.zone_name')->get();
        foreach($routes as $route){
        $route->drivers_count = DriverDetail::where('route_id', $route->id)->count();
        $route->shops_count = AddressBook::where('route_id', $route->id)->count();
        }
        return $routes;
    }
}
