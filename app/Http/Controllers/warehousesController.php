<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\WarehouseDetail;
class warehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::join('users', 'warehouses.user_id', '=', 'users.id')
                               ->select('users.f_name', 'users.l_name', 'warehouses.w_name', 'warehouses.id', 'warehouses.user_id', 'warehouses.location')
                               ->get();
        foreach($warehouses as $warehouse){
            $warehouse->stock = WarehouseDetail::where('warehouse_id', $warehouse->id)->sum('quantity');
        }
        return $warehouses;
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
            "w_name" => "required",
            "location" => "required",
            "user_id" => "required"
        ]);

        $warehouse = new Warehouse;
        $warehouse->w_name = $request->w_name;
        $warehouse->user_id = $request->user_id;
        $warehouse->location = json_encode($request->location);
        $warehouse->save();

        return $warehouse;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            "w_name" => "required",
            "user_id" => "required",
            "location" => "required"
        ]);

        $warehouse = Warehouse::find($id);
        $warehouse->w_name = $request->w_name;
        $warehouse->user_id = $request->user_id;
        $warehouse->location = json_encode($request->location);
        $warehouse->save();

        return $warehouse;
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
