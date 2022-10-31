<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;
class shopsController extends Controller
{
    public function getShops(){
        $shops = User::where('account_type', 'USER')->get();
        foreach($shops as $shop){
            $shop->address = AddressBook::leftJoin('zone_routes', 'address_books.route_id', '=', 'zone_routes.id')
                                        ->where('user_id', $shop->id)->get();
        }

        return $shops;
    }
}
