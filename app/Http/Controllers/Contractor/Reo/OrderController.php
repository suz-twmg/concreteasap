<?php

namespace App\Http\Controllers\Contractor\Reo;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractReoRequest;

use App\Repositories\Interfaces\Contractor\REO\OrderReoRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

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
        $order=$this->order_reo->createReo($request->all());
        if (!is_null($order)) {
            return response()->json(array("message" => "The Job has been placed"), 200);
        }
        else{
            return response()->json(array("message" => "There is an issue while placing a job."), 400);
        }
    }

    public function update(ContractReoRequest $request){
        $order=$this->order_reo->updateReo($request->all());
        if (!is_null($order)) {
            return response()->json(array("message" => "Successfully Updated"), 200);
        }
        else{
            return response()->json(array("message" => "There is an issue updating the job."), 400);
        }
    }
}
