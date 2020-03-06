<?php

namespace App\Models\Bids;

use App\Models\Order\BidMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Order\Order;

class Bid extends Model
{
    protected $fillable=['released'];
    protected $hidden = [
        "user_id"
    ];

    //
    public function order(){
        return $this->belongsTo('App\Models\Order\Order');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getAcceptedOrder(){
        return $this->with("order.orderConcrete","order.user");
    }

    public function getRejectedOrder(){
        return $this->with("order.orderConcrete");
    }

    public function getOrderType(){
        return $this->date_delivery===\Illuminate\Support\Carbon::now('Australia/Sydney')->format("Y-m-d")?"pour_orders":"accepted_orders";
    }

    /**
     * @method isCompleteorCancelled
     * @return bool
     */
    public function isCompleteOrCancelled(){
        return in_array($this->status,array("Complete","Cancelled","archive"));
    }

    public function isDayOfPour(){
        return $this->date_delivery===\Illuminate\Support\Carbon::now('Australia/Sydney')->format("Y-m-d");
    }
}
