<?php

namespace App\Actions;

use App\Models\Wallet;
use App\Models\WalletHistory;
use App\Services\ServiceService;
use Carbon\Carbon;
class OrderProductAction
{
    public function checkProductQty($products , $requestedOrder , $order=NULL) {
        $orderProducts = $order ? $order->products : NULL;
        $totalPrice = 0;
        $attachedProducts = [];
        foreach($products as $product) {
            if($orderProducts) {
                $orderProductQty = $orderProducts->where('id' , $product->id)->first()->pivot->qty;

                $productQty =  $product->qty;

                $qtyBefore = $orderProductQty + $productQty;

                $check_qty =  ($qtyBefore >= $requestedOrder[$product->id]['qty']);

            } else {
                $check_qty =  ($product->qty >= $requestedOrder[$product->id]['qty']);
                $orderProductQty = 0;
            }
           
         
           
           if(! $check_qty)
                return ['status' => false , 'data' => $product->name .' has Only '.$product->qty+$orderProductQty.' pieces'];
              

            $totalPrice += ($product->price * $requestedOrder[$product->id]['qty']);

            $attachedProducts[$product->id] = ['qty' => $requestedOrder[$product->id]['qty'] , 'price' => $product->price * $requestedOrder[$product->id]['qty'] , 'order_product_info' => json_encode([ 'id' => $product->id , 'name' => $product->name ]) ];

        }

        return ['status' => true , 'total_price' => $totalPrice , 'attached_products' => $attachedProducts];
    }


    public function updateProductQty($products , $requestedOrder , $order=NULL) {
        $orderProducts = $order ? $order->products : NULL;
        foreach($products as $product) {
            if($orderProducts) {
                $qtyBefore = $orderProducts->where('id' , $product->id)->first()->pivot->qty + $product->qty;
               
                $product->qty =  $qtyBefore - $requestedOrder[$product->id]['qty'];
            } else {

                $product->qty -= $requestedOrder[$product->id]['qty'];
            }

            $product->save();
        }
    }

}