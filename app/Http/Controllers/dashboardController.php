<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class dashboardController extends Controller
{
    public function salesThirty(){
        $data = [];
        $sales = Order::where('created_at', '>', Carbon::now()->subDays(30)->endOfDay())
                       ->sum('total');

        $salesPrevious = Order::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])
                                ->sum('total');

        $diff = $sales-$salesPrevious;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($sales > 0){
            $data['perecentage'] = floor((abs($diff)/$sales)*100);
            $data['sales'] = $sales;
        }
        else{
            $data['perecentage'] = 100;
            $data['sales'] = $sales;
        }


        return $data;
    }
    public function ordersThirty(){
        $data = [];
        $orders = Order::where('created_at', '>', Carbon::now()->subDays(30)->endOfDay())
                       ->count();

        $ordersPrevious = Order::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])
                                ->count();

        $diff = $orders-$ordersPrevious;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($orders > 0){
            $data['perecentage'] = floor((abs($diff)/$orders)*100);
            $data['orders'] = $orders;
        }
        else{
            $data['perecentage'] = 100;
            $data['orders'] = $orders;
        }
        

        return $data;
    }
    public function usersThirty(){
        $data = [];
        $users = User::where('account_type', 'USER')->where('created_at', '>', Carbon::now()->subDays(30)->endOfDay())
                       ->count();

        $usersPrevious = User::where('account_type', 'USER')->whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(30)])
                                ->count();

        $diff = $users-$usersPrevious;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($users > 0){
            $data['perecentage'] = floor((abs($diff)/$users)*100);
            $data['users'] = $users;
        }
        else{
            $data['perecentage'] = 100;
            $data['users'] = $users;
        }
        return $data;
    }

    public function ordersSeven(){
        $data = [];
        $labels = array("Processing","Shipped", "Delivered");
        $processing = Order::where('created_at', '>', Carbon::now()->subDays(7)->endOfDay())
                           ->where('order_status', 'PROCESSING')   
                           ->count();
        $shipped = Order::where('created_at', '>', Carbon::now()->subDays(7)->endOfDay())
                           ->where('order_status', 'SHIPPED')   
                           ->count();
        $delivered = Order::where('created_at', '>', Carbon::now()->subDays(7)->endOfDay())
                           ->where('order_status', 'DELIVERED')   
                           ->select('id','order_status')
                           ->count();
        $data["data"] = array($processing,$shipped,$delivered);
        $data["labels"] = $labels;
        return $data;
    }

    public function salesSeven(){
        
        $data = [];
        $data['labels'] = ["7d ago", "6d ago", "5d ago", "4d ago", "3d ago", "2d ago", "1d ago"];
        $data['data'] = [];
        for ($i=7; $i >0 ; $i--) { 
            $sale = Order::where('created_at', 'like', Carbon::now()->subDays($i)->toDateString().'%')
                         ->sum('total');
            array_push($data['data'], $sale);
        }
        return $data;
    }

    public function revenueYear(){
        $data = [];
        $sales = Order::whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()])
                      ->sum('total');
        $salesLast = Order::whereBetween('created_at', [Carbon::now()->subYear()->startOfYear(), Carbon::now()->subYear()])
                           ->sum('total');

        $diff = $sales-$salesLast;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($sales > 0){
            $data['perecentage'] = floor((abs($diff)/$sales)*100);
            $data['sales'] = $sales;
        }
        else{
            $data['perecentage'] = 100;
            $data['sales'] = $sales;
        }
        return $data;
    }

    public function salesToday(){
        $sales = Order::where('created_at', 'like', Carbon::now()->toDateString().'%')
                      ->sum('total');
        $salesPrevious = Order::where('created_at', 'like', Carbon::now()->subDays(1)->toDateString().'%')
                              ->sum('total');
        $diff = $sales-$salesPrevious;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($sales > 0){
            $data['perecentage'] = floor((abs($diff)/$sales)*100);
            $data['sales'] = $sales;
        }
        else{
            $data['perecentage'] = 100;
            $data['sales'] = $sales;
        }
        return $data;
    }
    public function ordersToday(){
        $data = [];
        $orders = Order::where('created_at', 'like', Carbon::now()->toDateString().'%')
                       ->count();

        $ordersPrevious = Order::where('created_at', 'like', Carbon::now()->subDays(1)->toDateString().'%')
                                ->count();

        $diff = $orders-$ordersPrevious;
        if($diff < 0){
            $data['type'] = "DEC";
        }
        else{
            $data["type"] = "INC";
        }
        if($orders > 0){
            $data['perecentage'] = floor((abs($diff)/$orders)*100);
            $data['orders'] = $orders;
        }
        else{
            $data['perecentage'] = 100;
            $data['orders'] = $orders;
        }
        

        return $data;
    }

    public function salesReport(Request $request){
        $this->validate($request, [
            "from" => "required",
            "to" => "required",
            "reportType" => "required"
        ]);

        $data = [];
        $data['data'] = [];
        $data['labels'] = [];
        if($request->reportType == 'monthly'){
            $months = $this->getMonthList(Carbon::parse($request->from), Carbon::parse($request->to));
            foreach($months as $month){
                $sales = Order::where('created_at', 'like', $month.'%')
                              ->sum('total');
                array_push($data['data'],$sales);
                array_push($data['labels'], $month);
            }
            return $data;
        }
        else{
            $days = $this->getDaysList(Carbon::parse($request->from), Carbon::parse($request->to));
            foreach($days as $day){
                $sales = Order::where('created_at', 'like', $day.'%')
                              ->sum('total');
                array_push($data['data'],$sales);
                array_push($data['labels'], $day);
            }
            return $data;
        }
        

    }
    public function getMonthList($from,$end)
    {
        $index = 0;
        foreach (CarbonPeriod::create($from, '1 month', $end) as $month) {
            $months[$index++] = $month->format('Y-m');
        }
        return $months;
    }
    public function getDaysList($from,$end)
    {
        $index = 0;
        foreach (CarbonPeriod::create($from, '1 day', $end) as $month) {
            $months[$index++] = $month->format('Y-m-d');
        }
        return $months;
    }
}
