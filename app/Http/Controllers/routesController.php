<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ZoneRoute;
use App\Models\DriverDetail;
class routesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routes = ZoneRoute::join('driver_details', 'driver_details.route_id', '=', 'zone_routes.id')
                           ->get();
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
        ]);

        $route = new ZoneRoute;
        $route->route_name = $request->route_name;
        $route->zone_id = $request->zone_id;
        $route->save();

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
