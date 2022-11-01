<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;
use App\Models\Balance;
use App\Models\BalanceHistory;
use DB;
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

    public function withdrawCash(Request $request){
        $this->validate($request, [
            "amount" => "required | integer"
        ]);

        try{
            DB::beginTransaction();
            $balance = Balance::where("user_id", auth()->user()->id)->lockForUpdate()->first();
            if($request->amount > $balance->balance){
                return response ('Amount exceeds agent balance', 422);
            }
            else{
                $balance->balance = $balance->balance - $request->amount;
                $balance->save();
            }

            $transaction = new BalanceHistory;
            $latestTransaction = BalanceHistory::orderBy('created_at','DESC')->first();
            if($latestTransaction){
                $transaction->transaction_no = '#'.str_pad($latestTransaction->id + 1, 8, "0", STR_PAD_LEFT);
            }
            else{
                $transaction->transaction_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
            }
            $transaction->amount = $request->amount;
            $transaction->user_id = auth()->user()->id;
            $transaction->transaction_type = 'Withdraw';
            $transaction->save();
            DB::commit();

            return $transaction;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
        }
        
    }
}
