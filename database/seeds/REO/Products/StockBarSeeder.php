<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class StockBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $stock_bar=orderReoCategory::where("slug","stock_bar")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"N12",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N16",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N20",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N24",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N28",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N32",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N36",
            "description"=>"Stock bar",
            "category_id"=>$stock_bar->id
        ]);

    }
}
