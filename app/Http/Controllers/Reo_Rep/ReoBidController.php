<?php

namespace App\Http\Controllers\Reo_Rep;

use App\Events\ReoRep\Bid\ReoBidOrderSuccess;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reo_Rep\ReoBidRequest;
use App\Models\Bids\Bid;
use App\Repositories\Rep_REO\REO\BidRepository;
use Illuminate\Http\Request;

class ReoBidController extends Controller
{
    private $reo_bid_repo;
    private $user;

    public function __construct(BidRepository $reo_bid_repo)
    {
        $this->reo_bid_repo=$reo_bid_repo;
        $this->user = auth('api')->user();
    }

    //
    public function bidReoOrder(Bid $bid, ReoBidRequest $reo_bid_request){

        $order=$this->reo_bid_repo->create($reo_bid_request,$this->user->id);
        event(new ReoBidOrderSuccess($order));
        return response()->json(["msg"=>"Your have successfully placed bid"],200);
    }

}
