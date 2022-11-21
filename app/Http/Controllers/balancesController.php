<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\BalanceHistory;
use App\Models\User;
class balancesController extends Controller
{
    public function getMyBalance(){
        $balance = Balance::where('user_id', auth()->user()->id)->first();
        return $balance;
    }

    public function balanceHistory(){
        $history = BalanceHistory::where('user_id', auth()->user()->id)
                                 ->orderBy('created_at', 'DESC')->paginate(5);
        return $history;
    }
}
