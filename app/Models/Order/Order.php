<?php

namespace App\Models\Order;

use App\Models\Bids\Bids;

use Illuminate\Database\Eloquent\Model;

use App\User;

use App\Models\Order\orderConcrete;

/**
 * @method static find(int $order_id)
 */
class Order extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        "user_id"
    ];
    //
    public function orderConcrete()
    {
        return $this->hasOne(orderConcrete::class,"order_id","id");
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function bids(){
        return $this->hasMany(Bids::class);
    }

    public  function review(){
        return $this->hasOne(orderReview::class,"order_id","id");
    }

//    public function confirmation(){
//        return $this->hasOne(orderConfirmation::class,"order_id","id");
//    }

    public function message(){
        return $this->hasMany(orderMessage::class,"order_id","id");
    }

    public function getAcceptedBidUser(){
        $bid=$this->bids()->where("status","=","Accepted")->first();
        $bid_user=$bid->user()->get();
        return $bid_user?$bid_user:null;
    }

    public function getAcceptedBid(){
        return (object)$this->bids()->with(["order.orderConcrete","order.user"])->where("status","=","Accepted")->first();
    }

    public function getRejectedBid(){
        $bid=$this->bids()->with(["order.orderConcrete"])->where("status","!=","Accepted")->first();
        return $bid;
    }
}
