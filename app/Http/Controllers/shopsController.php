<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShopManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ShopAssigned;

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
        $data['shop_details'] = User::select('users.f_name', 'users.shop_status', 'users.l_name', 'users.phone_no', 'users.created_at')->find($id);
        $data['average_order'] = $this->calculate_average_order($id);
        $shop_manager = ShopManager::where('shop_id', $id)->first();
        $data['sales_manager'] = ($shop_manager)?User::find($shop_manager->sales_manager):null;
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
        foreach($address as $ad){
            $ad->sales_manager = User::find($ad->sales_manager);
        }
        return $address;
    }

    public function verfiyShop(Request $request, $id){
        $this->validate($request, [
            "regular_address" => "required",
            "lng" => "required",
            "lat" => "required"
        ]);
        $geolocation = [];
        $geolocation['lat'] = $request->lat;
        $geolocation['lng'] = $request->lng;


        $shop = new AddressBook;
        $shop->user_id = $id;
        $shop->regular_address = $request->regular_address;
        $shop->geolocation = json_encode($geolocation);
        $shop->save();

        $user = User::find($id);
        $user->shop_status = 'VERIFIED';
        $user->save();

        return $shop;
    }

    public function addShop(Request $request, $id){
        $this->validate($request, [
            "regular_address" => "required",
            "lng" => "required",
            "lat" => "required"
        ]);
        $geolocation = [];
        $geolocation['lat'] = $request->lat;
        $geolocation['lng'] = $request->lng;


        $shop = new AddressBook;
        $shop->user_id = $id;
        $shop->regular_address = $request->regular_address;
        $shop->geolocation = json_encode($geolocation);
        $shop->save();

        return $shop;
    }

    public function updateShop(Request $request, $id){
        $this->validate($request, [
            "regular_address" => "required",
            "lng" => "required",
            "lat" => "required"
        ]);
        $geolocation = [];
        $geolocation['lat'] = $request->lat;
        $geolocation['lng'] = $request->lng;


        $shop = AddressBook::find($id);
        $shop->user_id = $request->user_id;
        $shop->regular_address = $request->regular_address;
        $shop->geolocation = json_encode($geolocation);
        $shop->save();

        return $shop;
    }

    public function searchCustomer(Request $request){
        $this->validate( $request, [
            "queryItem" => "required"
        ]);
        
        $user = User::where(function ($q) use($request){
                        return $q->where("phone_no", 'like', '%'.$request->queryItem.'%')
                                 ->orWhere("f_name", 'like', '%'.$request->queryItem.'%');
                    })
                    ->where('user_role', 'USER')
                    ->paginate(10);
                     
        return $user;
    }

    public function assignSalesManager(Request $request){
        $this->validate($request, [
            "sales_id" => "required",
            "shop_id" => "required"
        ]);
        
        $shop = new ShopManager;
        $shop->shop_id = $request->shop_id;
        $shop->sales_manager = $request->sales_id;
        $shop->save();

        $salesManager = User::find($request->sales_id);
        $user_message = "You have been assigned to verfiy a shop";
        Notification::send($salesManager, new ShopAssigned($user_message,$shop));
    
        return $shop;
    }

    public function updateSalesManager(Request $request, $id){
        $this->validate($request, [
            "sales_id" => "required"
        ]);

        $shop = ShopManager::where('shop_id', $id)->first();
        $shop->sales_manager = $request->sales_id;
        $shop->save();

        $salesManager = User::find($request->sales_id);
        $user_message = "You have been assigned to verfiy a shop";
        Notification::send($salesManager, new ShopAssigned($user_message,$shop));
        
        return $shop;
    }
}
