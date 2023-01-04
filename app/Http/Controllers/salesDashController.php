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
use App\Models\Visit;
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
use App\Exports\productSalesExport;
use App\Exports\customerSalesExport;
use App\Exports\agentSalesExport;
use App\Exports\rtmSalesExport;
use App\Exports\driverSalesExport;
use Maatwebsite\Excel\Facades\Excel;

class salesDashController extends Controller
{
    public function productsRank(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
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
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('quantity');
            $updated_quantity = Order::join('order_items', 'orders.id', 'order_items.order_id')
                                            ->where('order_status', 'DELIVERED')
                                            ->where('order_items.p_id', $product->id)
                                            ->where('order_items.item_status', 'UPDATED')
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('quantity');
            $product->total_quantity = $regular_quantity + $updated_quantity;
            $product->total_sold = $product->total_quantity * $product->price;
        }
        $sort = $products->sortByDesc($request->sort_by)->values()->all();
        $rank = 1;
        foreach($sort as $s){
            $s->rank = $rank++;
        }           
        return $sort;
    }

    public function bestSeller(){
        return $this->productsRank()[0];
    }
    public function worstSeller(){
        $rank = $this->productsRank();
        return end($rank);
    }

    public function getCustomersRank(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);

        $customers = User::where('user_role', 'USER')->select('f_name', 'l_name', 'country_code', 'phone_no', 'id')->get();
        foreach($customers as $customer){
            $customer->total_quantity = Order::where('order_status', 'DELIVERED')
                                             ->where('user_id', $customer->id)
                                             ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                             ->count();
            $customer->total_sold = Order::where('order_status', 'DELIVERED')
                                            ->where('user_id', $customer->id)
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('total');    
        }
        $sort = $customers->sortByDesc($request->sort_by)->values()->all();
        $rank = 1;
        foreach($sort as $s){
            $s->rank = $rank++;
        }           
        return $sort;
    }

    public function getAgentsRank(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);

        $customers = User::where('user_role', 'AGENT')->select('f_name', 'l_name', 'country_code', 'phone_no', 'id')->get();
        foreach($customers as $customer){
            $customer->total_quantity = Order::where('order_status', 'DELIVERED')
                                             ->where('agent_id', $customer->id)
                                             ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                             ->count();
            $customer->total_sold = Order::where('order_status', 'DELIVERED')
                                            ->where('agent_id', $customer->id)
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('total'); 
            $customer->commission = BalanceHistory::where('user_id', $customer->id)
                                            ->where('transaction_type', 'Commission')
                                            ->sum('amount');   
        }
        $sort = $customers->sortByDesc($request->sort_by)->values()->all();
        $rank = 1;
        foreach($sort as $s){
            $s->rank = $rank++;
        }           
        return $sort;
    }

    public function getRTMrank(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);

        $rtms = User::where('user_role', 'RTM')->select('f_name', 'l_name', 'country_code', 'phone_no', 'id')->get();
        foreach($rtms as $rtm){
            $rtm->total_quantity = Order::where('order_status', 'DELIVERED')
                                             ->where('rtm_id', $rtm->id)
                                             ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                             ->count();
            $rtm->total_sold = Order::where('order_status', 'DELIVERED')
                                            ->where('rtm_id', $rtm->id)
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('total');    
        }
        $sort = $rtms->sortByDesc($request->sort_by)->values()->all();
        $rank = 1;
        foreach($sort as $s){
            $s->rank = $rank++;
        }           
        return $sort;
    }
    public function getDriversRank(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);

        $drivers = User::where('user_role', 'DRIVER')->select('f_name', 'l_name', 'country_code', 'phone_no', 'id')->get();
        foreach($drivers as $driver){
            $driver->total_quantity = Order::where('order_status', 'DELIVERED')
                                             ->where('assigned_driver', $driver->id)
                                             ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                             ->count();
            $driver->total_sold = Order::where('order_status', 'DELIVERED')
                                            ->where('assigned_driver', $driver->id)
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('total');    
            $driver->visits = Visit::where('visit_status', 'COMPLETED')
                                   ->where('user_id', $driver->id)
                                   ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                        return $q->whereBetween('visits.updated_at', [$request->start_date, $request->end_date]);
                                    })
                                   ->count();
            $driver->commission = BalanceHistory::where('user_id', $driver->id)
                                                ->where('transaction_type', 'Visit Reward')
                                                ->sum('amount');

            $driver->distance_covered = Order::where('order_status', 'DELIVERED')
                                            ->where('assigned_driver', $driver->id)
                                            ->when($request->start_date != null && $request->end_date !=null , function($q) use($request){
                                                return $q->whereBetween('orders.updated_at', [$request->start_date, $request->end_date]);
                                            })
                                            ->sum('distance');
            $driver->time_to_delivery = $this->getTimeToDelivery($driver->id); 
        }
        $sort = $drivers->sortByDesc($request->sort_by)->values()->all();
        $rank = 1;
        foreach($sort as $s){
            $s->rank = $rank++;
        }           
        return $sort;
    }

    public function getTimeToDelivery($driver_id){
        $total = 0;
        $orders = Order::where('assigned_driver', $driver_id)
                       ->where('order_status', 'DELIVERED')
                       ->get();
        foreach($orders as $order){
            $total += $order->accepted_at->diffInHours($order->updated_at); 
        }
        if(count($orders)> 0){
            return $total/count($orders);
        }
        else{
            return 0;
        }
        
    }

    public function exportProductSales(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
        $productSales = $this->productsRank($request);
        
        //dd($productSales);
        $trimmed = $this->removeImage($productSales);
        
        return Excel::download(new productSalesExport(collect($trimmed), $request->start_date, $request->end_date, $request->sort_by), 'productSales.xlsx');
    }
    public function exportCustomerSales(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
        $customerSales = $this->getCustomersRank($request);
        
        //dd($productSales);
        $trimmed = $this->formatUserSales($customerSales);
        
        return Excel::download(new customerSalesExport(collect($trimmed), $request->start_date, $request->end_date, $request->sort_by), 'productSales.xlsx');
    }
    public function exportAgentSales(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
        $customerSales = $this->getAgentsRank($request);
        
        //dd($productSales);
        $trimmed = $this->formatUserSales($customerSales);
        
        return Excel::download(new agentSalesExport(collect($trimmed), $request->start_date, $request->end_date, $request->sort_by), 'productSales.xlsx');
    }
    public function exportRTMsales(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
        $customerSales = $this->getRTMrank($request);
        
        //dd($productSales);
        $trimmed = $this->formatUserSales($customerSales);
        
        return Excel::download(new rtmSalesExport(collect($trimmed), $request->start_date, $request->end_date, $request->sort_by), 'productSales.xlsx');
    }
    public function exportDriverSales(Request $request){
        $this->validate($request, [
            "sort_by" => "required"
        ]);
        $customerSales = $this->getDriversRank($request);
        
        //dd($productSales);
        $trimmed = $this->formatUserSales($customerSales);
        
        return Excel::download(new driverSalesExport(collect($trimmed), $request->start_date, $request->end_date, $request->sort_by), 'productSales.xlsx');
    }
    public function removeImage($items){
        foreach($items as $item){
            unset($item['p_image'], $item['id'], $item['price']);
        }
        return $items;
    }
    public function formatUserSales($items){
        foreach($items as $item){
            unset($item['country_code'], $item['phone_no'], $item['id']);
        }
        return $items;
    }

}
