<?php

namespace App\Http\Controllers\Contractor\Reo;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractReoRequest;

use App\Repositories\Interfaces\Contractor\REO\OrderReoRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user;
    private $order_reo;
    //
    public function __construct(OrderReoRepositoryInterface $order_reo){
//        $this->user=auth('api')->user();
        $this->order_reo=$order_reo;
    }

    public function create(ContractReoRequest $request){
        try{
            $this->order_reo->createReo($request);
            return response()->json(["msg"=>"You have successfully added a new Job."],200);
        }
        catch(\Exception $e){
            return response()->json(["msg"=>$e->getMessage()],400);
        }

    }

}
