<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DriverDetail;
use App\Models\Order;
use DB;
class driversController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drivers = User::where('user_role', 'DRIVER')->paginate(10);
        foreach($drivers as $driver){
            $driver->routes = DriverDetail::join('zone_routes', 'driver_details.route_id', '=', 'zone_routes.id')
                                            ->where('driver_details.driver_id', $driver->id)
                                            ->get();
        }
        
        
        return $drivers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {



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
            "f_name" => "required",
            "l_name" => "required",
            "l_plate" => "required",
            "route_id" => "required",
            "phone_no" => "required|unique:users"
        ]);
        try {

            DB::beginTransaction();
            $driver = new User;
            $driver->f_name = $request->f_name;
            $driver->l_name = $request->l_name;
            $driver->phone_no = $request->phone_no;
            $driver->account_type = "Staff";
            $driver->user_role = "DRIVER";
            $driver->save();
            
            foreach($request->route_id as $route){
                $driver_detail = new DriverDetail;
                $driver_detail->driver_id = $driver->id;
                $driver_detail->l_plate = $request->l_plate;
                $driver_detail->route_id = $route;
                $driver_detail->save();
            }

            DB::commit();

            return $driver;

        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Store error', 422);
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = DriverDetail::join('users', 'driver_details.driver_id', '=', 'users.id')
                                ->where('users.id',$id)->first();
        return $driver;
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
            "f_name" => "required",
            "l_name" => "required",
            "l_plate" => "required",
            "route_id" => "required"
        ]);
        try {

            DB::beginTransaction();
            $driver = User::find($id);
            $driver->f_name = $request->f_name;
            $driver->l_name = $request->l_name;
            $driver->phone_no = $request->phone_no;
            $driver->account_type = "Staff";
            $driver->user_role = "DRIVER";
            $driver->save();
    
            $driver_detail = DriverDetail::where('driver_id', $driver->id)->delete();

            foreach($request->route_id as $route){
                $driver_detail = new DriverDetail;
                $driver_detail->driver_id = $driver->id;
                $driver_detail->l_plate = $request->l_plate;
                $driver_detail->route_id = $route;
                $driver_detail->save();
            }
            
            DB::commit();

            return $driver;

        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Store error', 422);
        }
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

    public function getRouteDrivers($route_id){
        //dd((int)($route_id)==null?true:false);
        if((int)$route_id){
            $drivers = DriverDetail::join('users', 'driver_details.driver_id', '=', 'users.id')
                    ->where('route_id', $route_id)
                    ->get();
            foreach($drivers as $driver){
            $driver->active_assignments = Order::where('assigned_driver', $driver->id)
                                        ->where('order_status', 'SHIPPED')->count();
            }
            return $drivers;
        }
        else{
            $drivers = DriverDetail::join('users', 'driver_details.driver_id', '=', 'users.id')
                                    ->get();
            foreach($drivers as $driver){
            $driver->active_assignments = Order::where('assigned_driver', $driver->id)
                                        ->where('order_status', 'SHIPPED')->count();
            }
            return $drivers;
        }

    }
}
