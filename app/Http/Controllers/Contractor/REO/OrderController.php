<?php

namespace App\Http\Controllers\Contractor\REO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractReoRequest;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user;
    private $order_reo;
    //
    public function __construct(OrderRepositoryInterface $order_reo){
        $this->user=auth('api')->user();
        $this->order_reo=$order_reo;
    }

    public function create(ContractReoRequest $request){

    }

    public function getAllPendingOrders(){

    }



}
