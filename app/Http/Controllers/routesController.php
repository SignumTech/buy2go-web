<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZoneRoute;
use App\Models\DriverDetail;
use App\Models\AddressBook;
class routesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = ZoneRoute::join('zones', 'zone_routes.zone_id', '=', 'zones.id')
                           ->select('zone_routes.id', 'zone_routes.route_name', 'zones.zone_name')->get();
        foreach($routes as $route){
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
        ]);

        $route = ZoneRoute::find($id);
        $route->route_name = $request->route_name;
        $route->zone_id = $request->zone_id;
        $route->save();

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
}
