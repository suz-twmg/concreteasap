<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class SwimmingPoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $swimming_pool_reo=orderReoCategory::where("slug","swimming_pool_reo")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"S12 6M",
            "description"=>"Swimming Pool Reo",
            "category_id"=>$swimming_pool_reo->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"S12 9M",
            "description"=>"Swimming Pool Reo",
            "category_id"=>$swimming_pool_reo->id
        ]);
    }
}
