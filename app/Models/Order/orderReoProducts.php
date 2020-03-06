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
    protected $table="reo_products";
    public function orders()
    {
        return $this->belongsToMany(Order::class, "order_reo");
    }

}
