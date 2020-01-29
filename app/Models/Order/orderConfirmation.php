<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class orderConfirmation extends Model
{
    //
    public function order(){
        $this->belongsTo(Order::class,"id","order_id");
    }
}
