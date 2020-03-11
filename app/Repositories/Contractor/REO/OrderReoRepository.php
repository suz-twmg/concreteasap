<?php

namespace App\Repositories\Contractor\REO;

use App\Models\Order\Order;
use App\Models\Order\orderDetail;
use App\Models\Order\orderReo;
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

        $order_detail = new orderDetail();
        $order_detail->address = isset($order_request["address"]) ? $order_request["address"] : "";
        $order_detail->suburb = $order_request["suburb"];
        $order_detail->post_code = isset($order_request["post_code"]) ? $order_request["post_code"] : "";
        $order_detail->state = isset($order_request["state"]) ? $order_request["state"] : "";
        $order_detail->delivery_date = $order_request["delivery_date"];
        $order_detail->delivery_date1 = $order_request["delivery_date1"];
        $order_detail->delivery_date2 = $order_request["delivery_date2"];
        $order_detail->time_preference1 = $order_request["time_preference1"];
        $order_detail->time_preference2 = isset($order_request["time_preference2"]) ? $order_request["time_preference2"] : "";
        $order_detail->time_preference3 = isset($order_request["time_preference3"]) ? $order_request["time_preference3"] : "";
        $order_detail->time_deliveries = $order_request["time_deliveries"];
        $order_detail->urgency = $order_request["urgency"];
        $order_detail->preference = $order_request["preference"];
        $order_detail->delivery_instructions = isset($order_request["delivery_instructions"]) ? $order_request["delivery_instructions"] : "";
        $order_detail->special_instructions = isset($order_request["special_instructions"]) ? $order_request["special_instructions"] : "";
        $order_detail->order_id = $order->id;
        $order_detail->touch();

        try {
            DB::beginTransaction();
            $order->save();
            $order->orderDetail()->save($order_detail);

            if(!empty($order_request['products'])){
                foreach($order_request['products'] as $product_id){
                    $products = [];
                    $products['order_id'] = $order->id;
                    $products['product_id'] = $product_id;

                    $order->reoProducts()->attach($order->id, $products);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $order=null;
        }
        return $order;
    }

    public function updateReo($order_request)
    {
        $order = Order::find($order_request["order_id"]);

        $order_detail = [];
        $order_detail['address'] = isset($order_request["address"]) ? $order_request["address"] : "";
        $order_detail['suburb'] = $order_request["suburb"];
        $order_detail['post_code'] = isset($order_request["post_code"]) ? $order_request["post_code"] : "";
        $order_detail['state'] = isset($order_request["state"]) ? $order_request["state"] : "";
        $order_detail['delivery_date'] = $order_request["delivery_date"];
        $order_detail['delivery_date1'] = $order_request["delivery_date1"];
        $order_detail['delivery_date2'] = $order_request["delivery_date2"];
        $order_detail['time_preference1'] = $order_request["time_preference1"];
        $order_detail['time_preference2'] = isset($order_request["time_preference2"]) ? $order_request["time_preference2"] : "";
        $order_detail['time_preference3'] = isset($order_request["time_preference3"]) ? $order_request["time_preference3"] : "";
        $order_detail['time_deliveries'] = $order_request["time_deliveries"];
        $order_detail['urgency'] = $order_request["urgency"];
        $order_detail['preference'] = $order_request["preference"];
        $order_detail['delivery_instructions'] = isset($order_request["delivery_instructions"]) ? $order_request["delivery_instructions"] : "";
        $order_detail['special_instructions'] = isset($order_request["special_instructions"]) ? $order_request["special_instructions"] : "";
        $order_detail['order_id'] = $order->id;

        try {
            DB::beginTransaction();
            $order->orderConcrete()->update($order_detail);

            if(!empty($order_request['products'])){
                $order->reoProducts()->sync($order_request['products']);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $order=null;
        }
        return $order;
    }
}
