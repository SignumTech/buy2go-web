<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
class cartController extends Controller
{
    public function addToCart(Request $request){
        $this->validate($request, [
            "p_id" => "required",
            "quantity" => "required"
        ]);
        $product = Product::find($request->p_id);
        if($request->quantity < $product->minimum_order){
            return response("The quantity is less than the minimum order!", 422);
        }
        $cart = Cart::where('user_id', auth()->user()->id)
                         ->first();
        
        if($cart){
            
            $cart_item = CartItem::where('p_id',$request->p_id)->where('cart_id', $cart->id)->first();
            if($cart_item){
                $cart_item->quantity = $cart_item->quantity + $request->quantity;
                $cart_item->save();
            }
            else{
                $cart_item = new CartItem;
                $cart_item->cart_id = $cart->id;
                $cart_item->p_id = $request->p_id;
                $cart_item->quantity = $request->quantity;
                $cart_item->save();
            }
            
            $cart->quantity = CartItem::where('cart_id', $cart->id)->sum('quantity');
            return $cart;
        }
        else{
            $cart = new Cart;
            $cart->cart_status = "ACTIVE";
            $cart->user_id = auth()->user()->id;
            $cart->save();
            
            $cart_item = new CartItem;
            $cart_item->cart_id = $cart->id;
            $cart_item->p_id = $request->p_id;
            $cart_item->quantity = $request->quantity;
            $cart_item->save();
            
            $cart->quantity = CartItem::where('cart_id', $cart->id)->sum('quantity');
            return $cart;
        }
    }

    public function updateCart(Request $request){
        $this->validate($request, [
            "items" => "required"
        ]);

        $cart = Cart::where('user_id', auth()->user()->id)
                         ->first();
        
        $this->checkMinimumOrder(json_decode($request->items));
        foreach(json_decode($request->items) as $item){
            $cart_item = CartItem::where('cart_id', $cart->id)->where('p_id', $item->p_id)->first();
            $cart_item->quantity = $item->quantity;
            $cart_item->save();
        }

        return $cart;
    }
    public function checkMinimumOrder($items){
        foreach($items as $item){
            $product = Product::find($item->p_id);
            if($product->minimum_order > $item->quantity){
                return response("Some item quantities are below the minimum order requirement!", 422);
            }
        }
    }
    public function getMyCart(){
        
        $cart = Cart::join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
                    ->join('products','cart_items.p_id', '=', 'products.id')
                    ->where('user_id', auth()->user()->id)
                    ->select('cart_items.id', 'products.taxable', 'products.p_name', 'products.price', 'cart_items.quantity', 'cart_items.p_id', 'cart_items.cart_id', 'products.p_image')
                    ->get();
        return $cart;
    }

    public function deleteCartItem($id){

        $item = CartItem::find($id);
        $item->delete();

        return $item;

    }
}
