<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visit;
use App\Models\VisitDetail;
use App\Events\DriverAssigned;
use App\Notifications\VisitStatusUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use App\Events\VisitAssigned;
use App\Events\VisitLocation;
use App\Models\AddressBook;
use App\Models\ZoneRoute;
use App\Models\DriverDetail;
use App\Models\User;
use App\Models\BalanceHistory;
use App\Models\Balance;
use DB;
class visitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visits = Visit::join('zone_routes', 'visits.route_id', '=', 'zone_routes.id')
                       ->join('users', 'visits.user_id', '=', 'users.id')
                       ->select('visits.*', 'users.f_name', 'users.l_name', 'zone_routes.route_name')
                       ->get();

        return $visits;
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
            "route_id" => "required",
            "user_id" => "required",
            "commission" => "required",
        ]);
        try{
            DB::beginTransaction();
            $visit = new Visit;                
            $visit->visit_no = $this->getVisitNumber();
            $visit->route_id = $request->route_id;
            $visit->user_id = $request->user_id;
            $visit->commission = $request->commission;
            $visit->visit_status = 'PENDING_CONFIRMATION';
            $visit->save();

            $this->addVisitDetails($visit->route_id, $visit->id);
            //Notification
            $driver = User::find($visit->user_id);
            $message = 'You have been assigned a visit';
            Notification::send($driver, new VisitStatusUpdated($message,$visit));
            broadcast(new VisitAssigned($driver))->toOthers();
            DB::commit();
            return $visit;
            
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Visit Error', 422);
        }
    }

    public function getVisitNumber(){
        $latestVisit = Visit::orderBy('created_at','DESC')->first();
        if($latestVisit){
            return '#'.str_pad($latestVisit->id + 1, 8, "0", STR_PAD_LEFT);
        }
        else{
            return '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
        }
    }

    public function addVisitDetails($route_id, $visit_id){
        $shops = AddressBook::where('route_id', $route_id)->get();
        foreach($shops as $shop){
            $visit = new VisitDetail;
            $visit->visit_id = $visit_id;
            $visit->shop_id = $shop->id;
            $visit->save();
        }
        return $shops;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visit = Visit::find($id);
        $visit->route_name = ZoneRoute::find($visit->route_id)->route_name;
        $driver = User::find($visit->user_id);
        
        $visit->driver = $driver->f_name.' '.$driver->l_name;
        $visit->l_plate = DriverDetail::where('driver_id', $driver->id)->first()->l_plate;
        $visit->addresses = VisitDetail::join('address_books', 'visit_details.shop_id', '=', 'address_books.id')
                                       ->where('visit_id', $id)
                                       ->get();
        return $visit;
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
        //
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

    public function confirmShopVisit(Request $request, $id){
        $this->validate($request, [
            "lat" => "required",
            "lng" => "required",
            "shop_id" => "required"
        ]);
        try{
            DB::beginTransaction();
            $visit = VisitDetail::where('visit_id', $id)
                                ->where('shop_id', $request->shop_id)
                                ->first();
            
            $confirm_location = [];
            $confirm_location['lat'] = $request->lat;
            $confirm_location['lng'] = $request->lng;
            $visit->status = "VISITED";
            $visit->confirm_location = json_encode($confirm_location);
            $visit->save();
            if($this->checkCompletion($id)){
                return $this->completeVisit($id);
            }
            
            DB::commit();
            return $visit;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Completion Error', 422);
        }
    }

    public function checkCompletion($id){
        $visits = VisitDetail::where('visit_id', $id)->get();
        $completed_visits = VisitDetail::where('visit_id', $id)
                                       ->where('status', 'VISITED')
                                       ->get();
        return  (count($visits) == count($completed_visits))?true:false;                               
    }

    public function startVisit($id){
        $visit = Visit::find($id);
        $visit->visit_status = 'IN_PROGRESS';
        $visit->save();
    
        return $visit;
    }

    public function completeVisit($id){
        
            $visit = Visit::find($id);
            $visit->visit_status = 'COMPLETED';
            $visit->save();
            $this->transferRewardBalance($visit);
            
            
            return $visit;

    }

    public function transferRewardBalance($visit){
        $balance = Balance::where('user_id', $visit->commission)->first();
        $balance->balance = $balance->balance + $visit;
        $balance->save();
         //////create a transaction///////////
         $transaction = new BalanceHistory;
         $latestTransaction = BalanceHistory::orderBy('created_at','DESC')->first();
         if($latestTransaction){
             $transaction->transaction_no = '#'.str_pad($latestTransaction->id + 1, 8, "0", STR_PAD_LEFT);
         }
         else{
             $transaction->transaction_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
         }
         $transaction->amount = $visit->commission;
         $transaction->user_id = $visit->user_id;
         $transaction->visit_id = $visit->id;
         $transaction->transaction_type = 'Visit Reward';
         $transaction->save();
         
         return $transaction;
    }

    public function broadcastVisitLocation(Request $request, $id){
        $this->validate($request, [
            "lat" => "required",
            "lng" => "required",
        ]);

        $visit = Visit::find($id);
        broadcast(new VisitLocation($visit, (double)$request->lat, (double)$request->lng))->toOthers();
    }
}
