<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
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
        $order = Order::join('order_items', 'orders.id', 'order_items.order_id')
                      ->where('orders.id', $id)
                      ->first();
        return $order;
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
        $orders = Order::join("order_items", "orders.id", "=", "order_items.order_id")
                       ->where('orders.order_status', "PROCESSING")
                       ->get();
        return $orders;
    }

    public function getShipped(){
        $orders = Order::join("order_items", "orders.id", "=", "order_items.order_id")
                       ->where('orders.order_status', "SHIPPED")
                       ->get();
        return $orders;
    }

    public function getDelivered(){
        $orders = Order::join("order_items", "orders.id", "=", "order_items.order_id")
                       ->where('orders.order_status', "DELIVERED")
                       ->get();
        return $orders;
    }
}
