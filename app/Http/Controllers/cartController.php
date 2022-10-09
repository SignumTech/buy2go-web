<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
class cartController extends Controller
{
    public function addToCart(Request $request){
        $this->validate($request, [
            "items" => "required"
        ]);

        $cart = Cart::where('user_id', auth()->user()->id)
                         ->first();
        
        if($cart){
            foreach(json_decode($request->items) as $item){
                $cart_item = CartItem::where('p_id',$item->p_id)->where('cart_id', $cart->id)->first();
                if($cart_item){
                    $cart_item->quantity = $cart_item->quantity + $item->quantity;
                    $cart_item->save();
                }
                else{
                    $cart_item = new CartItem;
                    $cart_item->cart_id = $cart->id;
                    $cart_item->p_id = $item->p_id;
                    $cart_item->quantity = $item->quantity;
                    $cart_item->save();
                }
            }
            return $cart;
        }
        else{
            $cart = new Cart;
            $cart->cart_status = "ACTIVE";
            $cart->user_id = auth()->user()->id;
            $cart->save();
            foreach(json_decode($request->items) as $item){
                $cart_item = new CartItem;
                $cart_item->cart_id = $cart->id;
                $cart_item->p_id = $item->p_id;
                $cart_item->quantity = $item->quantity;
                $cart_item->save();
            }
            return $cart;
        }
    }

    public function getMyCart(){
        
        $cart = Cart::join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                    ->where('user_id', auth()->user()->id)
                    ->get();
        return $cart;
    }

    public function deleteCartItem($id){

        $item = CartItem::where('p_id', $id)->first();
        $item->delete();

        return $item;

    }
}
