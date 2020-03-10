<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class reoCategories extends Model
{
    protected $table="reo_categories";
    public function reoProducts()
    {
        return $this->hasMany(reoProducts::class);
    }
}
