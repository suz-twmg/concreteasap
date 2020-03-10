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
        $this->user=auth('api')->user();
        $this->order_reo=$order_reo;
    }

    public function create(ContractReoRequest $request){
        $validator = Validator::make($request->all(), [
            'suburb' => 'required|max:255',
            'delivery_date' => 'required',
            "time_preference1" => 'required',
            "time_preference2" => 'required',
            "time_preference3" => 'required',
            "time_deliveries" => 'required',
            "urgency" => 'required',
            "preference" => 'required',
            "products" => 'required'
        ]);

        if (!$validator->fails()) {
            $order=$this->order_reo->createReo($request->all());
            if (!is_null($order)) {
                return response()->json(array("message" => "The Job has been placed"), 200);
            }
            else{
                return response()->json(array("message" => "There is an issue while placing a job."), 200);
            }
        } else {
            return response()->json($validator->errors(), 400);
        }
       

    }

}
