<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Warehouse;
use App\Models\WarehouseDetail;
use App\Models\Balance;
use App\Models\BalanceHistory;
use App\Models\AddressBook;
use App\Models\Product;
use App\Events\DriverRejectedOrder;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Events\DriverAssigned;
use App\Events\ConfirmPickup;
use App\Events\ConfirmReturn;
use App\Events\ConfirmDelivery;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class salesDashController extends Controller
{
    public function productsRank(){
        /*$orders = Order::join('order_items', 'orders.id', 'order_items.order_id')
                       ->join('products', 'order_items.p_id', '=', 'products.id')
                       ->where('order_status', 'DELIVERED')
                       ->selectRaw('sum(order_items.quantity) total, p_id, p_name, p_image')
                       ->groupBy('p_id', 'p_name', 'p_image')
                       ->orderBy('total', 'DESC')
                       ->get();*/
        $products = Product::select('p_name', 'price', 'id', 'p_image')->get();
        foreach($products as $product){
            $regular_quantity = Order::join('order_items', 'orders.id', 'order_items.order_id')
                                            ->where('order_status', 'DELIVERED')
                                            ->where('order_items.p_id', $product->id)
                                            ->where('order_items.item_status', null)
                                            ->sum('quantity');
            $updated_quantity = Order::join('order_items', 'orders.id', 'order_items.order_id')
                                            ->where('order_status', 'DELIVERED')
                                            ->where('order_items.p_id', $product->id)
                                            ->where('order_items.item_status', 'UPDATED')
                                            ->sum('quantity');
            $product->total_quantity = $regular_quantity + $updated_quantity;
            $product->total_sold = $product->total_quantity * $product->price;
        }
                       
        
        return $products;
    }
}
