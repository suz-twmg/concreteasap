<?php


namespace App\Repositories\Rep_REO\REO;


use App\Http\Requests\Reo_Rep\ReoBidRequest;
use App\Models\Bids\Bid;
use App\Models\Order\Order;
use App\User;

class BidRepository
{

    public function __construct(){

    }


    //Store bid into the db
    public function create(ReoBidRequest $reo_bid_request,int $user_id):Order{
        $order=Order::findOrFail($reo_bid_request["order_id"]);

        return $order;
    }


}
