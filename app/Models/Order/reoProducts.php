<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class reoProducts extends Model
{
    public function reoCategories()
    {
        return $this->belongsTo(reoCategories::class, 'id', 'category_id');
    }

    public function order()
    {
        return $this->belongsToMany(Order::class, 'order_reo', "order_id","product_id");
    }
}
