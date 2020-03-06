<?php

namespace App\Repositories\Contractor\REO;

use App\Models\Order\Order;
use App\Models\Order\orderDetail;
use App\Models\Order\OrderReo;
use App\Repositories\Interfaces\Contractor\REO\OrderRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderRepository implements Interfaces\Contractor\REO\OrderRepositoryInterface
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

        try {
            DB::beginTransaction();
            $order->save();
            
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
