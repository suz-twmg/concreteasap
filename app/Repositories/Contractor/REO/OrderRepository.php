<?php

use App\Models\Order\Order;
use App\Models\Order\orderDetail;
use App\Repositories\Interfaces\Contractor\REO\OrderRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository implements OrderRepositoryInterface
{

    public function __construct()
    {

    }

    public function create($request,User $user){

        //Add New Order
        $order = new Order();
        //generate order id
        $order->order_hash_id = Str::random(8);
        $order->user_id = $user->id;
        $order->order_type = "concrete";
        $order->status = "Pending";
        $order->job_id = $order->generateCustomJobId();
        $order->touch();

        //H
        $order_detail=new orderDetail();
        $order_detail->address = isset($request["address"]) ? $request["address"] : "";
        $order_detail->suburb = $request["suburb"];
        $order_detail->post_code = isset($request["post_code"]) ? $request["post_code"] : "";
        $order_detail->state = isset($request["state"]) ? $request["state"] : "";
        $order_detail->delivery_date = $request["delivery_date"];
        $order_detail->delivery_date1 = $request["delivery_date1"];
        $order_detail->delivery_date2 = $request["delivery_date2"];
        $order_detail->time_preference1 = $request["time_preference1"];
        $order_detail->time_preference2 = isset($request["time_preference2"]) ? $request["time_preference2"] : "";
        $order_detail->time_preference3 = isset($request["time_preference3"]) ? $request["time_preference3"] : "";
        $order_detail->time_deliveries = $request["time_deliveries"];
        $order_detail->urgency = $request["urgency"];
        $order_detail->preference = $request["preference"];
        $order_detail->delivery_instructions = isset($request["delivery_instructions"]) ? $request["delivery_instructions"] : "";
        $order_detail->special_instructions = isset($request["special_instructions"]) ? $request["special_instructions"] : "";

//        $mesh=
//        $trench_mesh=
//            $stock_bar=
//        $
//        $order->orderReo()->attach([
//
//        ]);
        try {
            DB::beginTransaction();
            $order->save();
            $order->orderDetail()->save($order_detail);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $order=null;
        }


    }

}
