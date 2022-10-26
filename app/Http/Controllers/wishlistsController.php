<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
class wishlistsController extends Controller
{
    public function addToWishlist($id){
        $wishlist = new Wishlist;
        $wishlist->p_id = $id;
        $wishlist->user_id = auth()->user()->id;
        $wishlist->save();

        return $wishlist;
    }

    public function getMyWishlist(){
        $wishlist = Wishlist::join('products', 'wishlists.p_id', '=', 'products.id')
                            ->where('user_id', auth()->user()->id)
                            ->get();
        return $wishlist;
    }

    public function removeFromWishlist($id){
        $item = Wishlist::where('p_id', $id)
                        ->where('user_id', auth()->user()->id)
                        ->first();
        $item->delete();
        return $item;
    }
}
