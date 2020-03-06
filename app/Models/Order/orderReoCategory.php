<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class orderReoCategory extends Model
{
    protected $table="reo_categoies";
    //

    public function products(){
        return $this->hasMany(orderReoProducts::class);
    }
}
