<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\WarehouseDetail;
use App\Exports\WarehouseExport;
use Maatwebsite\Excel\Facades\Excel;
class warehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        foreach($warehouses as $warehouse){
            $user = User::find($warehouse->user_id);
            if($user){
                $warehouse->f_name = $user->f_name;
                $warehouse->l_name = $user->l_name; 
            }
            else{
                $warehouse->f_name = null;
                $warehouse->l_name = null;
            }
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
        $warehouse = Warehouse::find($id);
        $warehouse->delete();

        return $warehouse;
    }

    public function filterWarehouses(Request $request){
        $warehouses = $this->filterData($request)->select('warehouses.id', 'warehouses.w_name','users.f_name', 'users.l_name', 'warehouses.location')->get();
        foreach($warehouses as $warehouse){
            $warehouse->stock = WarehouseDetail::where('warehouse_id', $warehouse->id)->sum('quantity');
        }

        return $warehouses;
    }

    public function exportWarehouses(Request $request){
        $warehouses = $this->filterData($request)->select('warehouses.id', 'warehouses.w_name','users.f_name', 'users.l_name', 'warehouses.location')->get();
        foreach($warehouses as $warehouse){
            $warehouse->stock = WarehouseDetail::where('warehouse_id', $warehouse->id)->sum('quantity');
        }
        return Excel::download(new WarehouseExport($warehouses), 'warehouses.xlsx');
    }

    public function filterData(Request $request){
        return Warehouse::join('users', 'warehouses.user_id', '=', 'users.id')
                        ->when($request->w_name !=null, function ($q) use($request){
                            return $q->where('w_name', 'LIKE', '%'.$request->w_name.'%');
                        })
                        ->when($request->user_id !=null, function ($q) use($request){
                            return $q->where('user_id', $request->user_id);
                        });        
    }

    public function addWarehouseManager(Request $request){
        $this->validate($request, [
            "f_name" => "required",
            "l_name" => "required",
            "country_code" => "required",
            "phone_no" => "required | unique:users"
        ]);

        $manager = new User;
        $manager->f_name = $request->f_name;
        $manager->l_name = $request->l_name;
        $manager->country_code = $request->country_code;
        $manager->phone_no = $request->phone_no;
        $manager->account_type = 'WAREHOUSE_MANAGER';
        $manager->user_role = 'WAREHOUSE_MANAGER';
        $manager->save();

        return $manager;
    }

    public function updateWarehouseManager(Request $request, $id){
        $this->validate($request, [
            "f_name" => "required",
            "l_name" => "required",
            "country_code"=>"required",
            "phone_no" => "required"
        ]);

        $manager = User::find($id);
        $manager->f_name = $request->f_name;
        $manager->l_name = $request->l_name;
        $manager->country_code = $request->country_code;
        $manager->phone_no = $request->phone_no;
        $manager->account_type = 'WAREHOUSE_MANAGER';
        $manager->user_role = 'WAREHOUSE_MANAGER';
        $manager->save();

        return $manager;
    }

    public function getDeletedWarehouses(){
        $warehouses = Warehouse::onlyTrashed()->join('users', 'warehouses.user_id', '=', 'users.id')
                               ->select('users.f_name', 'users.l_name', 'warehouses.w_name', 'warehouses.id', 'warehouses.user_id', 'warehouses.location', 'warehouses.created_at', 'warehouses.deleted_at')
                               ->get();
        foreach($warehouses as $warehouse){
            $warehouse->stock = WarehouseDetail::where('warehouse_id', $warehouse->id)->sum('quantity');
        }
        return $warehouses;
    }

    public function restoreWarehouse($id){
        $warehouse = Warehouse::withTrashed()->find($id);
        $warehouse->restore();

        return $warehouse;
    }

    public function deleteWarehouseManager($id){
        if(auth()->user()->user_role == 'ADMIN'){
            $manager = User::find($id);
            $manager->delete();
        }
        else{
            return response("Unauthorized", 401);
        }
        
    }
        

}
