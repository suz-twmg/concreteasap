<?php

namespace App\Models\Order;

use App\Models\Bids\Bids;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\User;

use App\Models\Order\orderConcrete;
use App\Models\Order\orderReo;

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

    protected $fillable=[
        "status"
    ];
    //
    public function orderConcrete()
    {
        return $this->hasOne(orderConcrete::class,"order_id","id");
    }

    public function orderDetail(){
        $this->hasOne(orderDetail::class,"order_id","id");
    }

    public function orderReo(){
        return $this->hasMany(orderReo::class);
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

    public function getContractorPendingOrders(User $user){

    }

    public function generateCustomJobId(){
        $current_date=Carbon::now();
        $month=$current_date->format("m");
        $year=$current_date->format("y");
        $job_id="0-".$month."-".$year;

        $last_job=Order::orderBy('id','desc')->first();

        if(!is_null($last_job)){
            $last_job_id=$last_job->job_id;
            $last_job_arr=explode("-",$last_job_id);
            if(isset($last_job_arr[1])&&isset($last_job_arr[2])){
                if($last_job_arr[1]===$month&&$last_job_arr[2]===$year){
                    $id=((int)$last_job_arr[0])+1;
                    $job_id=$id."-".$month."-".$year;
                }
            }
        }
        return $job_id;
    }

    public function scopeAuthuser($query){
        return $query->where("user_id",'=',Auth::user()->id);
    }

    public function isAwaiting(){
        return in_array($this->status,array("Waiting Payment Confirmation"));
    }

    public function isPaid(){
        return in_array($this->status,array("Paid"));
    }

    public function isReleased(){
        return in_array($this->status,array("Released"));
    }

    public function isCompleteOrCancelled(){
        return in_array($this->status,array("Complete","Cancelled","archive"));
    }
}
