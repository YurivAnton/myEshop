<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_product extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function products()
    {
        return $this->belongsToMany('App\Product', 'order_products');
    }
}
