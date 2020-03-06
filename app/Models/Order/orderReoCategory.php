<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class orderReoCategory extends Model
{
    //

    public function products(){
        return $this->hasMany(orderReoProducts::class);
    }
}
