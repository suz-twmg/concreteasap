<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class reoCategories extends Model
{
    public function reoProducts()
    {
        return $this->hasMany(reoProducts::class);
    }
}
