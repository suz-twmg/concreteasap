<?php

namespace App\Models\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\User;

use App\Models\Order\orderReo;

/**
* @method static find(int $order_id)
*/
class orderReoProducts extends Model
{
    public function order(){
        return $this->belongsTo(orderReo::class);
    }
}
