<?php

namespace App\Models\Bids;

use App\Models\Order\BidMessage;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Order\Order;

class Bids extends Model
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
}
