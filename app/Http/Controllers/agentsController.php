<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;

class agentsController extends Controller
{
    public function searchShop(Request $request){
        $this->validate($request, [
            "searchQuery" => "required"
        ]);

        $shops = User::where('account_type', 'USER')
                        ->where(function($query) use($request){
                            $query->where('f_name', 'LIKE', '%'.$request->searchQuery.'%')
                                ->orWhere('phone_no', 'LIKE', $request->searchQuery.'%');
                        })
                        ->get();
        foreach($shops as $shop){
            $shop->address = AddressBook::where('user_id', $shop->id)->get();
        }

        return $shops;
    }

    public function getAgents(){
        $agents = User::join('balances', 'users.id', 'balances.user_id')
                      ->where('account_type', 'AGENT')
                      ->get();
        return $agents;
    }
}