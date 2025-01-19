<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order_info = json_decode($this->order_info , true);
        return [
            'id'                    => $this->id,
            'status'                    => $this->status,
            'user'                  => $order_info,
            'total_price'           => $this->total_price,
            'products' => $this->orderProducts->transform(function($product){
                $product_info = json_decode($product->order_product_info , true);
                return [
                    'id'        => $product_info['id'],
                    'name'        => $product_info['name'],
                    'qty'      => $product->qty,
                    'price'      => $product->price
                ];
            }),
            'payment'               => $this->payment ? json_decode($this->payment->payment_info , true) : NULL
        ];
    }
}
