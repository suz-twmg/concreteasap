<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class orderReo extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }

    public function reoProducts()
    {
        return $this->hasOne(reoProducts::class, 'id', 'product_id');
    }

}
