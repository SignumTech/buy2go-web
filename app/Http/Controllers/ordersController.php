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

class ordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        if(auth()->user()->user_role == 'RTM'){
            $orders = Order::where('rtm_id', auth()->user()->id)
                            ->orderBy("created_at", "DESC")
                            ->paginate(10);
            return $orders;
        }
        $orders = Order::orderBy("created_at", "DESC")->paginate(10);
        return $orders;
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
            'cart_id' => 'required | integer',
            'total' => 'required | numeric',
            'address' => 'required | integer'
        ]);
        try{
            DB::beginTransaction();

            $order = new Order;
            $latestOrder = Order::orderBy('created_at','DESC')->first();
            if($latestOrder){
                $order->order_no = '#'.str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
            }
            else{
                $order->order_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
            }
            $order->total = $request->total;
            $order->order_status = 'PROCESSING';
            $order->user_id = auth()->user()->id;
            $order->delivery_details = $request->address;
            $order->payment_status = 'UNPAID';
            $order->rtm_id = $this->loadBalancer();
            $order->save();

            $cart_items = Cart::join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                        ->where('carts.id', $request->cart_id)->get();

            foreach($cart_items as $item){
                $order_details = new OrderItem;
                $order_details->order_id = $order->id;
                $order_details->p_id = $item->p_id;
                $order_details->quantity = $item->quantity;
                $order_details->save();
                /*$product_stock = WarehouseDetail::where('p_id', $product->id)->sum('quantity');
                if($item->quantity > $product_stock){
                    return response("Some of the items have been sold out", 422);
                }
                else{
                    
                }*/
            }
            $cart = Cart::find($request->cart_id);
            $cart->delete();

            //Notification
            $admin = User::where('user_role', 'ADMIN')->get();
            $rtm = User::find($order->rtm_id);
            $message = 'A new order has been placed';
            Notification::send($admin, new OrderStatusUpdated($message,$order));
            Notification::send($rtm, new OrderStatusUpdated($message,$order));
            DB::commit();
            return $order;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
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
        $order = Order::find($id);
        $order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                            ->where('order_items.order_id', $id)
                            ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                            ->get();
        $delivery_details = AddressBook::find($order->delivery_details);

        $data = [];
        $data['order_details'] = $order;
        $data['order_hash'] = Hash::make($order->order_no);
        $data['order_items'] = $order_items;
        $data['delivery_details'] = $delivery_details;
        return $data;
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

    public function loadBalancer(){
        $rtms = User::where('user_role', 'RTM')
                    ->where('online_status', 'ONLINE')
                    ->get();
        $data = [];
        foreach($rtms as $rtm){
            $row = [];
            $row[$rtm->id] = Order::where('rtm_id', $rtm->id)
                                 ->where('order_status', 'PROCESSING')
                                 ->count();
            $data = $data + $row;
            //array_push($data, $row);
        }
        //var_dump($data);
        //dd(array_search(min($data), $data));
        if(count($rtms) > 0){
            return array_search(min($data), $data);
        }
        else{
            return null;
        }
        
    }

    public function addAgentOrder(Request $request){
        $this->validate($request, [
            'cart_id' => 'required | integer',
            'total' => 'required | numeric',
            'address' => 'required | integer',
            'user_id' => 'required'
        ]);
        try{
            DB::beginTransaction();

            $order = new Order;
            $latestOrder = Order::orderBy('created_at','DESC')->first();
            if($latestOrder){
                $order->order_no = '#'.str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
            }
            else{
                $order->order_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
            }
            $order->total = $request->total;
            $order->order_status = 'PROCESSING';
            $order->user_id = $request->user_id;
            $order->delivery_details = $request->address;
            $order->payment_status = 'UNPAID';
            $order->agent_id = auth()->user()->id;
            $order->order_type = "AGENT_ORDER";
            $order->rtm_id = $this->loadBalancer();
            $order->save();

            $cart_items = Cart::join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                        ->where('carts.id', $request->cart_id)->get();

            $agent_commission = 0;

            foreach($cart_items as $item){
                $order_details = new OrderItem;
                $order_details->order_id = $order->id;
                $order_details->p_id = $item->p_id;
                $order_details->quantity = $item->quantity;
                $order_details->save();
            }

            //////delete cart////////////////////
            $cart = Cart::find($request->cart_id);
            $cart->delete();
            //Notification
            $admin = User::where('user_role', 'ADMIN')->get();
            $message = 'A new order has been placed';
            Notification::send($admin, new OrderStatusUpdated($message,$order));
            
            DB::commit();
            return $order;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
        }
    }

    public function getProcessing(){
        $orders = Order::where('orders.order_status', "PROCESSING")
                       ->when(auth()->user()->user_role == 'RTM', function ($q) {
                            $q->where('rtm_id', auth()->user()->id);
                       })
                       ->orderBy("created_at", "DESC")
                       ->paginate(10);
        return $orders;
    }

    public function getShipped(){
        $orders = Order::where('orders.order_status', "SHIPPED")
                        ->when(auth()->user()->user_role == 'RTM', function ($q) {
                            $q->where('rtm_id', auth()->user()->id);
                        })
                       ->orderBy("created_at", "DESC")
                       ->paginate(10);
        return $orders;
    }

    public function getDelivered(){
        $orders = Order::where('orders.order_status', "DELIVERED")
                        ->when(auth()->user()->user_role == 'RTM', function ($q) {
                            $q->where('rtm_id', auth()->user()->id);
                        })
                       ->orderBy("created_at", "DESC")
                       ->paginate(10);
        return $orders;
    }

    public function getPendingConfirmation(){
        $orders = Order::where('orders.order_status', "PENDING_CONFIRMATION")
                        ->when(auth()->user()->user_role == 'RTM', function ($q) {
                                $q->where('rtm_id', auth()->user()->id);
                        })
                       ->orderBy("created_at", "DESC")
                       ->paginate(10);
        return $orders;
    }

    public function getPendingPickup(){
        $orders = Order::where('orders.order_status', "PENDING_PICKUP")
                        ->when(auth()->user()->user_role == 'RTM', function ($q) {
                            $q->where('rtm_id', auth()->user()->id);
                        })
                       ->orderBy("created_at", "DESC")
                       ->paginate(10);
        return $orders;
    }

    public function assignDetails(Request $request, $id){
        $this->validate($request, [
            "warehouse_id" => "required",
            "driver_id" => "required",
        ]);
        try{
            DB::beginTransaction();
            $order = Order::find($id);
            $order->assigned_driver = $request->driver_id;
            $order->warehouse_id = $request->warehouse_id;
            $order->order_status = 'PENDING_CONFIRMATION';
            $order->save();
    
            $items = OrderItem::where('order_id', $order->id)->get();
            foreach($items as $item){
                $warehouse_detail = WarehouseDetail::where('p_id', $item->id)
                                                   ->where('warehouse_id', $request->warehouse_id)
                                                   ->first();
                if($warehouse_detail){
                    $warehouse_detail->quantity = $warehouse_detail->quantity - $item->quantity;
                    $warehouse_detail->save();
                }
                
            }
            
            DB::commit();
            $driver = User::find($order->assigned_driver);
            $message = 'You have been assigned to delivery order_no '.$order->order_no;
            Notification::send($driver, new OrderStatusUpdated($message,$order));
            broadcast(new DriverAssigned($driver))->toOthers();
            return $order;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
        }

    }

    public function getDriverPendingOrders(){
        $data = [];
        $index = 0;
        $orders = Order::where('assigned_driver', auth()->user()->id)
                       ->where( function ($query){
                            $query->where('order_status', 'PENDING_CONFIRMATION')
                                  ->orWhere('order_status', 'PENDING_PICKUP');
                       })
                       ->get();
        foreach($orders as $order){
            $data[$index]['order_hash'] = Hash::make($order->order_no);
            $data[$index]['order_detail'] = $order;
            $data[$index]['delivery_detail'] = AddressBook::where('user_id', $order->user_id)->first();
            $data[$index]['order_items'] = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                    ->where('order_id', $order->id)->get();
            $data[$index]['warehouse_detail'] = Warehouse::where('id', $order->warehouse_id)->first();
            $index++;
        }
        
        
        return $data;
    }

    public function getDriverShipped(){
        $order = Order::where('assigned_driver', auth()->user()->id)
                      ->where('order_status', 'SHIPPED')
                      ->get();

        return $order;
    }

    public function getDriverDelivered(){
        $order = Order::where('assigned_driver', auth()->user()->id)
                      ->where('order_status', 'DELIVERED')
                      ->get();

        return $order;
    }

    public function getDriverOrders(){

        $orders = Order::where('assigned_driver', auth()->user()->id)
                       ->orderBy('created_at', 'DESC')
                       ->paginate(12);
        foreach($orders as $order){
            $order->order_hash = Hash::make($order->order_no);
            $order->delivery_detail = AddressBook::where('user_id', $order->user_id)->first();
            $order->order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                    ->where('order_id', $order->id)
                                                    ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                                                    ->get();
            $order->warehouse_detail = Warehouse::where('id', $order->warehouse_id)->first();
        }
        
        
        return $orders;
    }

    public function acceptOrder($id){
        $order = Order::find($id);
        if(auth()->user()->id !=  $order->assigned_driver){
            return response("Unauthorized",401);
        }
        $order->order_status = "PENDING_PICKUP";
        $order->save();

        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $driver = User::find($order->assigned_driver)->f_name;
        $message = $driver.' accepted order number. '.$order->order_no;
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        //broadcast(new DriverRejectedOrder($order))->toOthers();

        return $order;
    }

    public function rejectOrder($id){
        $order = Order::find($id);
        if(auth()->user()->id !=  $order->assigned_driver){
            return response("Unauthorized",401);
        }
        $order->order_status = "PROCESSING";
        $order->save();

        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $driver = User::find($order->assigned_driver)->f_name;
        $message = $driver.' rejected order number. '.$order->order_no;
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        //broadcast(new DriverRejectedOrder($order))->toOthers();
        return $order;
    }

    public function confirmPickup(Request $request, $id){
        $this->validate($request, [
            "order_hash" => "required"
        ]);
        
        $order = Order::find($id);
        if(!Hash::check($order->order_no, $request->order_hash)){
            return response("Unauthorized",401);
        }
        $this->updateInventory($id);
        $this->recordShippedQuantity($id);
        $order->order_status = "SHIPPED";
        $order->save();

        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $driver = User::find($order->assigned_driver);
        $message = $driver->f_name.' picked up order number. '.$order->order_no.' from warehouse';
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        broadcast(new ConfirmPickup($order))->toOthers();
        return $order;
    }
    
    public function updateInventory($id){
        $order = Order::find($id);
        $items = OrderItem::where('order_id', $id)->get();
        foreach($items as $item){
            $warehouse_item = WarehouseDetail::where('p_id', $item->p_id)
                                             ->where('warehouse_id', $order->warehouse_id)
                                             ->first();
            if($item->item_status == 'UPDATED'){
                $warehouse_item->quantity = $warehouse_item->quantity - $item->updated_quantity;
                $warehouse_item->save();
            }
            else{
                $warehouse_item->quantity = $warehouse_item->quantity - $item->quantity;
                $warehouse_item->save();
            }
        }
        return $order;

    }

    public function confirmDelivery(Request $request, $id){
        $this->validate($request,[
            "payment_method" => "required",
            "payment_status" => "required"
        ]);
        
        $order = Order::find($id);
        if(auth()->user()->id !=  $order->user_id){
            return response("Unauthorized",401);
        }
        $order->tx_ref = $request->tx_ref;
        $order->reference = $request->reference;
        $order->payment_status = $request->payment_status;
        if($request->payment_method == 'Credit'){
            $order->credit_deadline = Carbon::now()->addDays((int)$request->credit_time);
        }
        $order->payment_method = $request->payment_method;
        
        $order->order_status = "DELIVERED";
        $order->save();

        if($order->order_type == 'AGENT_ORDER'){
            $this->calculateAgentCommission($order);
        }
        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $driver = User::find($order->assigned_driver);
        $message = "Order ".$order->order_no." was delivered successfully.";
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        broadcast(new ConfirmDelivery($order))->toOthers();
        return $order;
    }

    public function calculateAgentCommission($order){
        $items = OrderItem::where('order_id', $order->id)->get();
        $agent_commission = 0;
        foreach($items as $item){
            $product = Product::find($item->p_id);
            $agent_commission = $agent_commission + ($product->price * $product->commission/100);
        }
    
        //////update agent balance///////////
        $balance = Balance::where('user_id', $order->agent_id)->first();
        $balance->balance = $balance->balance + $agent_commission;
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
        $transaction->amount = $agent_commission;
        $transaction->user_id = auth()->user()->id;
        $transaction->order_id = $order->id;
        $transaction->transaction_type = 'Commission';
        $transaction->save();
        
        return $transaction;
    }
    
    public function getMyOrders(){
        $orders = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(12);
        foreach($orders as $order){
            $order->items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                     ->where('order_items.order_id', $order->id)
                                     ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                                     ->get();
        }
        return $orders;
    }

    public function getMyShippedOrders(){
        $orders = Order::where('user_id', auth()->user()->id)
                        ->where('order_status', 'SHIPPED')
                        ->get();
        return $orders;
    }

    public function getMyDeliveredOrders(){
        $orders = Order::where('user_id', auth()->user()->id)
                        ->where('order_status', 'DELIVERED')
                        ->get();
        return $orders;
    }

    public function getMyPendingOrders(){
        $orders = Order::where('user_id', auth()->user()->id)
                        ->where('order_status', 'PROCESSING')
                        ->get();
        return $orders;
    }

    public function getOrderDetails($id){
        $order = Order::join('warehouses', 'orders.warehouse_id', '=', 'warehouses.id')
                      ->join('users', 'orders.assigned_driver', '=', 'users.id')
                      ->join('driver_details', 'users.id', '=', 'driver_details.driver_id')
                      ->where('orders.id', $id)
                      ->first();
        return $order;
    }

    public function repurchaseOrder($id){
        $orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                       ->where('orders.id', $id)
                       ->get();
        
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        if($cart){
            foreach($orders as $order){
                $cartItem = CartItem::where('p_id', $order->p_id)
                                    ->where('cart_id', $cart->id)
                                    ->first();
                if($cartItem){
                    $cartItem->quantity = $cartItem->quantity + $order->quantity;
                    $cartItem->save();
                }
                else{
                    $cartItem = new CartItem;
                    $cartItem->p_id = $order->p_id;
                    $cartItem->quantity = $order->quantity;
                    $cartItem->cart_id = $cart->id;
                    $cartItem->save();
                }
            }
            return $cart;
        }
        else{
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->save();

            foreach($orders as $order){
                $cartItem = new CartItem;
                $cartItem->p_id = $order->p_id;
                $cartItem->quantity = $order->quantity;
                $cartItem->cart_id = $cart->id;
                $cartItem->save();
            }
            return $cart;
        }
    }

    public function getWarehouseOrders(){
        $warehouse = Warehouse::where('user_id', auth()->user()->id)->first();
        if($warehouse){
            $orders = Order::where('warehouse_id', $warehouse->id)
                       ->where('order_status', 'PENDING_PICKUP')
                       ->orderBy('created_at', 'DESC')
                       ->paginate(8);
            foreach($orders as $order){
                $order->order_hash = Hash::make($order->order_no);
                $order->delivery_detail = AddressBook::where('user_id', $order->user_id)->first();
                $order->order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                        ->where('order_id', $order->id)
                                                        ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                                                        ->get();
                $order->warehouse_detail = Warehouse::where('id', $order->warehouse_id)->first();
            }
            return $orders;
        }
        else{
            return response("No warehouses available", 401);
        }
    }

    public function filterOrders(Request $request){
        
        $orders = $this->filteredData($request)->paginate(10);
        return $orders;
    }

    public function exportOrders(Request $request){
        $orders = $this->filteredData($request)->select('id', 'order_no', 'total', 'order_status', 'payment_status', 'payment_method', 'order_type', 'created_at');
        return Excel::download(new OrdersExport($orders->get()), 'orders.xlsx');
    }

    public function filteredData(Request $request){
        $orders = Order::when($request->order_no !=null, function ($q) use($request){
            return $q->where('order_no', $request->order_no);
        })
        ->when($request->payment_status !=null, function ($q) use($request){
            return $q->where('payment_status', $request->payment_status);
        })
        ->when($request->order_status !=null, function ($q) use($request){
            return $q->where('order_status', $request->order_status);
        })
        ->when($request->payment_method !=null, function ($q) use($request){
            return $q->where('payment_method', $request->payment_method);
        })
        ->when(auth()->user()->user_role == 'RTM', function ($q) {
            $q->where('rtm_id', auth()->user()->id);
        });

        return $orders;
    }

    public function updateItemQuantity(Request $request, $id){
        $this->validate($request, [
            "items" => "required"
        ]);
        $items = json_decode($request->items);
        $order = Order::find($id);
        if($order->order_status == 'DELIVERED'){
            return response("Unable to edit! Order already delivered.", 422);
        }
        $returnCount = 0;
        foreach($items as $j_item){
            $item = OrderItem::find($j_item->id);
            $product = Product::find($j_item->p_id);   
            if($order->order_status == "SHIPPED"){

                if($this->checkQuantity($j_item, $item)){
                    return response('Item is already shipped', 422);
                }
                $item->item_status = 'UPDATED';
                $item->last_updated_by = 'USER';
                $item->updated_quantity = $j_item->updated_quantity;
                $item->save();

                $returnCount++;
                       
            }    
            else{
                $item->item_status = 'UPDATED';
                $item->last_updated_by = 'USER';
                $item->updated_quantity = $j_item->updated_quantity;
                $item->save();
            }

        }
        
        //update orders total
        $order = $this->calculateTotal($order);
        $order->return_status = ($returnCount > 0)?"HAS_RETURNS":"NO_RETURNS";
        $order->save();

        $admin = User::where('user_role', 'ADMIN')->get();
        $admin_message = 'User made changes to order '.$order->order_no;
        Notification::send($admin, new OrderStatusUpdated($admin_message,$order));
        return $order;
    }

    public function checkQuantity($j_item, $item){
        if($item->item_status == 'UPDATED'){
            return ($j_item->updated_quantity > $item->updated_quantity)?true:false;
        }
        else{
            return ($j_item->updated_quantity > $item->quantity)?true:false;
        }
    }

    public function updateWarehouseItemQuantity(Request $request, $id){
        $this->validate($request, [
            "items" => "required"
        ]);
        $items = json_decode($request->items);
        $order = Order::find($id);
        if($order->order_status == 'DELIVERED'){
            return response("Unable to edit! Order already delivered.", 422);
        }
        foreach($items as $j_item){
            $item = OrderItem::find($j_item->id);
            $product = Product::find($j_item->p_id);   
            if($order->order_status == "SHIPPED" || $order->order_status == "DELIVERED"){
                return response("Order is already shipped. Unable to update order", 422);
            }
            else{
                if(!$this->checkQuantity($j_item, $item)){
                    $item->item_status = 'UPDATED';
                    $item->last_updated_by = 'WAREHOUSE';
                    $item->warehouse_limit = $j_item->updated_quantity;
                    $item->updated_quantity = $j_item->updated_quantity;
                    $item->save();
                    
                }
            }
        }

        $order = $this->calculateTotal($order);
        $order->save();

        $admin = User::where('user_role', 'ADMIN')->get();
        $user = User::find($order->user_id);
        $admin_message = 'Warehouse manager made changes to order '.$order->order_no;
        $user_message = "There are some changes on order ".$order->order_no;
        Notification::send($user, new OrderStatusUpdated($user_message,$order));
        Notification::send($admin, new OrderStatusUpdated($admin_message,$order));
        return $order;
    }

    public function removeItem($id){
        $item = OrderItem::find($id);
        $product = Product::find($item->p_id);  
        $order = Order::find($item->order_id);
        $warehouse = Warehouse::find($order->warehouse_id);
        if(auth()->user()->id == $order->user_id || auth()->user()->id == $warehouse->user_id){
            if(auth()->user()->user_role == 'USER' && ($order->order_status == 'PROCESSING' || $order->order_status == 'PENDING_CONFIRMATION' || $order->order_status == 'PENDING_PICKUP')){
                
                $item->delete();
                $order = $this->calculateTotal($order);
                
                $order->save();
                return $item;
            }
            if(auth()->user()->user_role == 'USER' && $order->order_status == 'SHIPPED'){
                $item->item_status = 'USER_REMOVED';
                $item->last_updated_by = 'USER';
                $item->updated_quantity = 0;
                $item->save();

                $order = $this->calculateTotal($order);
                $order->return_status = "HAS_RETURNS";
                $order->save();

                $admin = User::where('user_role', 'ADMIN')->get();
                $message = 'An item was removed from order '.$order->order_no;
                Notification::send($admin, new OrderStatusUpdated($message,$order));
                return $item;
            }
            if(auth()->user()->user_role == 'WAREHOUSE_MANAGER' && ($order->order_status == 'PROCESSING' || $order->order_status == 'PENDING_CONFIRMATION' || $order->order_status == 'PENDING_PICKUP')){
                $item->item_status = 'WAREHOUSE_REMOVED';
                $item->last_updated_by = 'WAREHOUSE';
                $item->updated_quantity = 0;
                $item->save();

                $order = $this->calculateTotal($order);
                $order->save();
                $admin = User::where('user_role', 'ADMIN')->get();
                $user = User::find($order->user_id);
                $message = 'An item was removed from order '.$order->order_no;
                $user_message = "An item was removed from order ".$order->order_no;
                Notification::send($user, new OrderStatusUpdated($user_message,$order));
                Notification::send($admin, new OrderStatusUpdated($message,$order));
                return $item;
            }
            
            return $item;
        }
        else{
            return response('Unauthorized', 401);
        }
        
    }

    public function calculateTotal($order){
        $items = OrderItem::where('order_id', $order->id)->get();
        $total = 0;
        foreach($items as $item){
            $product = Product::find($item->p_id);
            if($item->item_status == 'UPDATED'){
                $total = $total + $this->calculateTax($product, $item->updated_quantity);
            }
            elseif($item->item_status == 'USER_REMOVED' || $item->item_status == 'WAREHOUSE_REMOVED'){
                $total = $total + $this->calculateTax($product, $item->updated_quantity);
            }
            else{
                $total = $total + $this->calculateTax($product, $item->quantity);
            }
        }
        $order->total = $total;
        return $order;
    }

    public function calculateTax($product, $quantity){
        if($product->taxable){
            return $product->price * $quantity * 1.15;
        }
        else{
            return $product->price * $quantity ;
        }
    }

    public function getReturnOrders(){
        $orders = Order::where('assigned_driver', auth()->user()->id)
                       ->where('return_status', 'HAS_RETURNS')
                       ->orderBy('created_at', 'DESC')
                       ->paginate(12);
        foreach($orders as $order){
            $order->order_hash = Hash::make($order->order_no);
            $order->delivery_detail = AddressBook::where('user_id', $order->user_id)->first();
            $order->order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                    ->where('order_id', $order->id)
                                                    ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                                                    ->get();
            $order->warehouse_detail = Warehouse::where('id', $order->warehouse_id)->first();
        }

        return $orders;
    }

    public function getWarehouseReturnOrders(){
        $warehouse = Warehouse::where('user_id', auth()->user()->id)->first();
        if($warehouse){
            $orders = Order::where('warehouse_id', $warehouse->id)
                       ->where(function($q){
                            return $q->where('order_status', 'DELIVERED')
                                     ->orWhere('order_status', 'CANCELED');
                       })
                       ->where('return_status', 'HAS_RETURNS')
                       ->orderBy('created_at', 'DESC')
                       ->paginate(8);
            foreach($orders as $order){
                $order->order_hash = Hash::make($order->order_no);
                $order->delivery_detail = AddressBook::where('user_id', $order->user_id)->first();
                $order->order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                        ->where('order_id', $order->id)
                                                        ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                                                        ->get();
                $order->warehouse_detail = Warehouse::where('id', $order->warehouse_id)->first();
            }
            return $orders;
        }
        else{
            return response("No warehouses available", 401);
        }
    }

    public function confirmReturn(Request $request, $id){
        $this->validate($request, [
            "order_hash" => "required"
        ]);
        
        $order = Order::find($id);
        if(!Hash::check($order->order_no, $request->order_hash)){
            return response("Unauthorized",401);
        }
        $this->updateReturnInventory($id);
        $order->return_status = "RETURNED";
        $order->save();

        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $driver = User::find($order->assigned_driver);
        $message = $driver->f_name.' returned items to warehouse for order . '.$order->order_no.' from warehouse';
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        broadcast(new ConfirmReturn($order))->toOthers();
        return $order;
    }

    public function updateReturnInventory($id){
        $order = Order::find($id);
        $items = OrderItem::where('order_id', $id)->get();
        foreach($items as $item){
            $warehouse_item = WarehouseDetail::where('p_id', $item->p_id)
                                             ->where('warehouse_id', $order->warehouse_id)
                                             ->first();
            if($item->item_status == 'UPDATED' || $item->item_status == 'USER_REMOVED'){
                $warehouse_item->quantity = $warehouse_item->quantity + ($item->shipped_quantity - $item->updated_quantity);
                $warehouse_item->save();
            }
            
        }
        return $order;
    }

    public function getReturnOrderDetails($id){
        $order = Order::find($id);
        $order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                            ->where('order_items.order_id', $id)
                            ->when($order->order_status != 'CANCELED',function($q){
                                $q->where('order_items.item_status', 'UPDATED')
                                  ->orWhere('order_items.item_status', 'USER_REMOVED');
                            })
                            
                            ->select('order_items.*', 'products.p_name', 'products.price', 'products.description', 'products.p_image', 'products.cat_id', 'products.commission', 'products.p_status', 'products.sku', 'products.taxable', 'products.deleted_at')
                            ->get();
        
        $items = [];
        foreach($order_items as $item){
            $item->return_quantity = $item->shipped_quantity - $item->updated_quantity;
            if($item->shipped_quantity - $item->updated_quantity > 0 || $item->item_status == `USER_REMOVED`){
                array_push($items, $item);
            }
        }
        
        $delivery_details = AddressBook::find($order->delivery_details);

        $data = [];
        $data['order_details'] = $order;
        $data['order_hash'] = Hash::make($order->order_no);
        $data['order_items'] = $items;
        $data['delivery_details'] = $delivery_details;
        return $data;
    }

    public function recordShippedQuantity($id){
        $order = Order::find($id);
        $items = OrderItem::where('order_id', $id)->get();
        foreach($items as $item){
            if($item->item_status == 'UPDATED'){
                $item->shipped_quantity = $item->updated_quantity;
                $item->save();
            }
            else{
                $item->shipped_quantity = $item->quantity;
                $item->save();
            }
        }
        return $order;
    }

    public function cancelOrder($id){

        $order = Order::find($id);
        if(auth()->user()->id != $order->user_id){
            return response('Unauthorized', 401);
        }
        if($order->order_status == 'SHIPPED'){
            $order->return_status = 'HAS_RETURNS';
        }
        if($order->order_status == 'DELIVERED'){
            return response('Order is already delivered', 422);
        }
        $order->order_status = 'CANCELED';
        $order->save();

        //Notification
        $admin = User::where('user_role', 'ADMIN')->get();
        $rtm = User::find($order->rtm_id);
        $message = 'Order number '.$order->order.' has been canceled.' ;
        Notification::send($admin, new OrderStatusUpdated($message,$order));
        Notification::send($rtm, new OrderStatusUpdated($message,$order));
        return $order;
    }

    
}
