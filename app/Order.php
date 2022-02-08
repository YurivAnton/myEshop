<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function order_products()
    {
        return $this->hasMany('App\order_product');
    }
    public function products()
    {
        return $this->belongsToMany('App\Product', 'order_products');
    }
}
