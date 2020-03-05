<?php

namespace App\Models\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\User;

use App\Models\Order\Order;

/**
* @method static find(int $order_id)
*/
class OrderReo extends Model
{
    public function order(){
        return $this->belongsTo(Order::class,'id','order_id');
    }

    public function products(){
        return $this->hasMany(orderReoProducts::class);
    }
}
