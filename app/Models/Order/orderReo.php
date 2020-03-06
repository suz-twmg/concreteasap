<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderReo extends Model
{
    public function order()
    {
        return $this->belongsToMany(Order::class, 'id', 'order_id');
    }


}
