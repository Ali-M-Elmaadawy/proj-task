<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id' , 'status' , 'order_info' , 'total_price'
    ];

    public function user() {

        return $this->belongsTo(User::class);
    }

    public function products() {

        return $this->belongsToMany(Product::class , 'order_products')->withPivot('qty' , 'price' , 'order_product_info');
    }

    public function orderProducts() {

        return $this->hasMany(OrderProduct::class);
    }

    public function payment() {

        return $this->hasOne(Payment::class);
    }

}
   
             