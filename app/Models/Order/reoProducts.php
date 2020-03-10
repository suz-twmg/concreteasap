<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class reoProducts extends Model
{
    public function reoCategories()
    {
        return $this->belongsTo(reoCategories::class, 'id', 'category_id');
    }

    public function orderReo()
    {
        return $this->belongsTo(orderReo::class, 'id', 'product_id');
    }
}
