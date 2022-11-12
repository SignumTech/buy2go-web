<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;
use App\Models\Order;
use App\Models\OrderItem;
class shopsController extends Controller
{
    public function getShops(){
        $shops = User::where('account_type', 'USER')->paginate(10);
        foreach($shops as $shop){
            $shop->address = AddressBook::where('user_id', $shop->id)->get();
        }

        return $shops;
    }

    public function getShopsWithNoRoutes(){
        $shops = User::where('account_type', 'USER')->get();
        foreach($shops as $shop){
            $shop->address = AddressBook::where('user_id', $shop->id)
                                        ->where('route_id', null)->get();
        }

        return $shops;
    }

    public function shopDetails($id){
        $data = [];
        $data['shop_details'] = User::select('users.f_name', 'users.l_name', 'users.phone_no', 'users.created_at')->find($id);
        $data['average_order'] = $this->calculate_average_order($id);
        $last_order = Order::where('user_id', $id)->latest('created_at')->select('order_no', 'created_at')->first();
        if($last_order){
            $data['last_order'] = $last_order;
        }
        else{
            $data['last_order']['order_no'] = null;
        }
        
        return $data;
        
    }

    public function calculate_average_order($id){
        $total_orders = Order::where('user_id', $id)->sum('total');
        $order_count = Order::where('user_id', $id)->count();
        if($order_count > 0){
            return round($total_orders/$order_count,2);
        }
        else{
            return 0;
        }
        
    }

    public function shopOrders($id){
        $orders = Order::where('user_id', $id)->get();
        foreach($orders as $order){
            $order->items_count = OrderItem::where('order_id', $order->id)->count();
        }
        $sum = Order::where('user_id', $id)->sum('total');
        $count = Order::where('user_id', $id)->count();
        $data = [];
        $data['total_spent'] = $sum;
        $data['orders_count'] = $count;
        $data['orders'] = $orders;
        return $data;
    }

    public function shopLocations($id){
        $address = AddressBook::where('user_id', $id)->get();
        return $address;
    }
}
