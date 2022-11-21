<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AddressBook;
use App\Models\Balance;
use App\Models\BalanceHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentRequest;
use App\Models\Warehouse;
use App\Events\CashWithdrawn;
use DB;
use Illuminate\Support\Facades\Hash;
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
                      ->paginate(10);
        return $agents;
    }

    public function withdrawCash(Request $request, $id){
        $this->validate($request, [
            "request_no" => "required"
        ]);

        try{
            DB::beginTransaction();
            $payment_request = PaymentRequest::find($id);

            if($payment_request->user_id != auth()->user()->id || !Hash::check($payment_request->request_no,$request->request_no)){
                return response('Unauthorized', 401);
            }
            $payment_request->request_status = 'PAID';
            $payment_request->save();

            $balance = Balance::where("user_id", auth()->user()->id)->lockForUpdate()->first();
            if($payment_request->amount > $balance->balance){
                return response ('Amount exceeds agent balance', 422);
            }
            else{
                $balance->balance = $balance->balance - $payment_request->amount;
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
            $transaction->amount = $payment_request->amount;
            $transaction->user_id = auth()->user()->id;
            $transaction->transaction_type = 'Withdraw';
            $transaction->save();
            DB::commit();
            
            broadcast(new CashWithdrawn($payment_request))->toOthers();
            return $transaction;
        }
        catch (\Exception $e) {
            DB::rollBack();

            throw $e;
            return response('Order Error', 422);
        }
        
    }
    
    public function agentDetails($id){
        $data = [];
        $data['agent_details'] = User::select('users.f_name', 'users.shop_status', 'users.l_name', 'users.phone_no', 'users.created_at')->find($id);
        $data['average_order'] = $this->calculate_agent_average_order($id);
        $data['agent_balance'] = Balance::where('user_id', $id)->select('balance')->first()->balance;
        $last_order = Order::where('agent_id', $id)->latest('created_at')->select('order_no', 'created_at')->first();
        if($last_order){
            $data['last_order'] = $last_order;
        }
        else{
            $data['last_order']['order_no'] = null;
        }
        
        return $data;
    }

    public function calculate_agent_average_order($id){
        $total_orders = Order::where('agent_id', $id)->sum('total');
        $order_count = Order::where('agent_id', $id)->count();
        if($order_count > 0){
            return round($total_orders/$order_count,2);
        }
        else{
            return 0;
        }
    }

    public function agentOrders($id){
        $orders = Order::where('agent_id', $id)->get();
        foreach($orders as $order){
            $order->items_count = OrderItem::where('order_id', $order->id)->count();
        }
        $sum = Order::where('agent_id', $id)->sum('total');
        $count = Order::where('agent_id', $id)->count();
        $data = [];
        $data['total_spent'] = $sum;
        $data['orders_count'] = $count;
        $data['orders'] = $orders;
        return $data;
    }

    public function placeRequest(Request $request){
        $this->validate($request, [
            "amount" => "required"
        ]);

        $paymentRequest = new PaymentRequest;
        $latestRequests = PaymentRequest::orderBy('created_at','DESC')->first();
        if($latestRequests){
            $paymentRequest->request_no = '#'.str_pad($latestRequests->id + 1, 8, "0", STR_PAD_LEFT);
        }
        else{
            $paymentRequest->request_no = '#'.str_pad(1, 8, "0", STR_PAD_LEFT);
        }
        $paymentRequest->amount = $request->amount;
        $paymentRequest->user_id = auth()->user()->id;
        $paymentRequest->save();

        return $paymentRequest;
    }

    public function getPaymentRequests(){
        return PaymentRequest::join('users', 'payment_requests.user_id', '=', 'users.id')
                             ->select(
                                'payment_requests.id', 
                                'payment_requests.request_no',
                                'payment_requests.amount',
                                'payment_requests.created_at',
                                'users.f_name',
                                'users.l_name',
                                'payment_requests.request_status')
                             ->paginate(7);
    }

    public function showPaymentRequest($id){
        $paymentRequest = PaymentRequest::join('users', 'payment_requests.user_id', '=', 'users.id')
                              ->join('balances', 'users.id', '=', 'balances.user_id')
                             ->where('payment_requests.id',$id)
                             ->first();
        $paymentRequest->request_hash = Hash::make($paymentRequest->request_no);

        return $paymentRequest;
    }

    public function getMyRequests(){
        return PaymentRequest::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(12);
    }

    public function confirmRequest($id){
        $payment = PaymentRequest::find($id);
        $balance = Balance::where('user_id', $payment->user_id)->first()->balance;
        if($payment->amount > $balance){
            return response("Can not confirm request. Request amount exceeds agent balance!", 422);
        }
        $payment->request_status = 'CONFIRMED';
        $payment->save();

        return $payment;
    }

    public function verfiyAgent(Request $request, $id){
        $agent = User::find($id);
        $agent->shop_status = 'VERIFIED';
        $agent->save();
        
        return $agent;
    }

    public function getAgentOrders(){
        $orders = Order::where('agent_id', auth()->user()->id)
                       ->orderBy('created_at', 'DESC')
                       ->paginate(12);
        foreach($orders as $order){
            
            
            $order->delivery_detail = AddressBook::where('user_id', $order->user_id)->first();
            $order->order_items = OrderItem::join('products', 'order_items.p_id', '=', 'products.id')
                                                    ->where('order_id', $order->id)->get();
            $order->warehouse_detail = Warehouse::where('id', $order->warehouse_id)->first();
        }
        
        
        return $orders;
    }


}
