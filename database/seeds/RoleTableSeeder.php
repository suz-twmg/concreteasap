<?php

use App\Models\Order\Order;
use App\Models\Order\orderDetail;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products=orderReoProducts::find(1);
        //
        factory(Order::class)->create([
            'order_hash_id'=>"",
            "user_id"=>2,
            "status"=>"Pending",
            "order_type"=>"reo",
            "job-id"=>"02-03-20"
        ])->save(function($order) use($products){
            $order->products()->sync($products);
        });

        factory(orderDetail::class)->create([
            'address'=>"50 Delhi Road",
            "suburb"=>"North Ryde",
            "post_code"=>2144,
            "state"=>"NSW",
            "delivery_date"=>"2020-03-20",
            "delivery_date1"=>"2020-03-22",
            "delivery_date2"=>"2020-03-24",
            "time_preference1"=>"10:00",
            "time_preference2"=>"12:00",
            "time_preference3"=>"13:00",
            "urgency"=>"Immediate",
            "preference"=>"test",
            "delivery_instructions"=>"",
            "special_instructions"=>"",
            "order_id"=>$order->id
        ]);
    }
}
