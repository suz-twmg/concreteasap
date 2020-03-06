<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class AccesoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $accesories=orderReoCategory::where("slug","accessories")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"Abelflex Plain",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"Abelflex Adhesive",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"Stiff Mastic Joints",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);
    }
}
