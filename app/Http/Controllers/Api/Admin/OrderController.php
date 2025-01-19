<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Http\Resources\Admin\OrdersCollection;
class OrderController extends Controller
{
       
    public function list() {
        $orders = Order::with('payment' , 'products');

        if(request('status')) {
            $orders->where('status' , request('status'));
        }
        $orders = $orders->paginate(5);

        return response()->json(['status' => true,'data' => OrdersCollection::collection($orders)->response()->getData(true)] , 200);
    }  

    public function list_of_payments() {
        $orders = Order::has('payment');

        if(request('status')) {
            $orders->where('status' , request('status'));
        }
        $orders = $orders->paginate(5);

        return response()->json([
            'status' => true,
            'data' => OrdersCollection::collection($orders)->response()->getData(true)
        ] , 200);
    }  

    public function update_status(Order $order) {
        $status = request('status');
        if($order->status != 'pending')
            return response()->json(['status' => false , 'data' => 'only Pending Orders Can Be Updated'] , 403);


        if(! in_array($status, ['confirmed' , 'cancelled']))
        return response()->json(['status' => false , 'data' => 'order status can only be confirmed or cancelled'] , 403);

        $order->status = $status;
        $order->save();

        if($status == 'cancelled') {
            foreach($order->products as $product) {
                $product->qty += $product->pivot->qty;
                $product->save();
            }
        }

        return response()->json(['status' => true , 'data' => 'order status updated success'] , 200);

    }
}
