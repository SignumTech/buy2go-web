<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\WarehouseDetail;
use DB;
class ordersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $orders = Order::all();
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

            $order = new Order();
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
            $order->payment_status = $request->payment_status;
            $order->tx_ref = $request->tx_ref;
            $order->reference = $request->reference;
            $order->payment_status = $request->payment_status;
            $order->payment_method = $request->payment_method;
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
        $order_items = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
                            ->join('products', 'order_items.p_id', '=', 'products.id')
                            ->where('orders.id', $id)
                            ->get();

        $data = [];
        $data['order_details'] = $order;
        $data['order_items'] = $order_items;
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

    public function getProcessing(){
        $orders = Order::where('orders.order_status', "PROCESSING")
                       ->get();
        return $orders;
    }

    public function getShipped(){
        $orders = Order::where('orders.order_status', "SHIPPED")
                       ->get();
        return $orders;
    }

    public function getDelivered(){
        $orders = Order::where('orders.order_status', "DELIVERED")
                       ->get();
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
            return $order;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
        }

    }

    public function getDriverPendingOrders(){
        $orders = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                       ->join('warehouses', 'orders.warehouse_id', '=', 'warehouse_id')
                       ->where('assigned_driver', auth()->user()->id)
                       ->where( function ($query){
                            $query->where('order_status', 'PENDING_CONFIRMATION')
                                  ->orWhere('order_status', 'PENDING_PICKUP');
                       })
                       ->get();
        
        return $orders;
    }

    public function getDriverOrders(){
        $orders = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                       ->where('assigned_driver', auth()->user()->id)
                       ->join('warehouses', 'orders.warehouse_id', '=', 'warehouse_id')
                       ->get();
        return $orders;
    }

    public function acceptOrder($id){
        $order = Order::find($id);
        $order->order_status = "PENDING_PICKUP";
        $order->save();

        return $order;
    }

    public function rejectOrder($id){
        $order = Order::find($id);
        $order->order_status = "PROCESSING";

        return $order;
    }
}
