<?php

namespace App\Repositories\Contractor\REO;

use App\Models\Order\Order;
use App\Models\Order\orderDetail;
use App\Models\Order\OrderReo;
use App\Repositories\Interfaces\Contractor\REO\OrderReoRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderReoRepository implements OrderReoRepositoryInterface
{
    private $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function createReo($order_request)
    {
        $order = new Order();
        //generate order id
        $order->order_hash_id = Str::random(8);
        $order->user_id = $this->user->id;
        $order->order_type = "reo";
        $order->status = "Pending";
        $order->job_id = $order->generateCustomJobId();
        $order->touch();

        $order_details = new orderDetail();
        $order_details->address = isset($order_request["address"]) ? $order_request["address"] : "";
        $order_details->suburb = $order_request["suburb"];
        $order_details->post_code = isset($order_request["post_code"]) ? $order_request["post_code"] : "";
        $order_details->state = isset($order_request["state"]) ? $order_request["state"] : "";
        $order_details->type = $order_request["type"];
        $order_details->mpa = $order_request["mpa"];
        $order_details->agg = $order_request["agg"];
        $order_details->slump = $order_request["slump"];
        $order_details->acc = $order_request["acc"];
        $order_details->placement_type = $order_request["placement_type"];
        $order_details->delivery_date = $order_request["delivery_date"];
        $order_details->delivery_date1 = $order_request["delivery_date1"];
        $order_details->delivery_date2 = $order_request["delivery_date2"];
        $order_details->quantity = $order_request["quantity"];
        $order_details->time_preference1 = $order_request["time_preference1"];
        $order_details->time_preference2 = isset($order_request["time_preference2"]) ? $order_request["time_preference2"] : "";
        $order_details->time_preference3 = isset($order_request["time_preference3"]) ? $order_request["time_preference3"] : "";
        $order_details->time_deliveries = $order_request["time_deliveries"];
        $order_details->urgency = $order_request["urgency"];
        $order_details->message_required = $order_request["message_required"];
        $order_details->preference = $order_request["preference"];
        $order_details->colours = isset($order_request["colours"]) ? $order_request["colours"] : "";
        $order_details->delivery_instructions = isset($order_request["delivery_instructions"]) ? $order_request["delivery_instructions"] : "";
        $order_details->special_instructions = isset($order_request["special_instructions"]) ? $order_request["special_instructions"] : "";
        $order_details->order_id = $order->id;
        $order_details->touch();

        try {
            DB::beginTransaction();
            $order->save();
            $order->orderConcrete()->save($order_concrete);

            if(!empty($order_request['products'])){
                foreach($order_request['products'] as $product){
                    $order_reo = new OrderReo();
                    $order_reo->order_id = $order->id;
                    $order_reo->product_id = $product;

                    $order->orderReo()->save($order_reo);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $order=null;
        }
        return $order;
    }
}
