<?php

namespace App\Http\Controllers\Contractor\REO;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContractReoRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $user;
    //
    public function __construct(){
        $this->user=auth('api')->user();
    }

    public function create(ContractReoRequest $request){
        
    }

    public function getAllPendingOrders(){

    }



}
