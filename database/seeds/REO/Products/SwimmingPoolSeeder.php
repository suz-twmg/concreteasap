<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use App\Models\Order\reoCategories;
use App\Models\Order\reoProducts;
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
        $swimming_pool_reo=reoCategories::where("slug","swimming_pool_reo")->first();

        factory(reoProducts::class)->create([
            "name"=>"S12 6M",
            "description"=>"Swimming Pool Reo",
            "category_id"=>$swimming_pool_reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"S12 9M",
            "description"=>"Swimming Pool Reo",
            "category_id"=>$swimming_pool_reo->id
        ]);
    }
}
