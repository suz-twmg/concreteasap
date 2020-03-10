<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use App\Models\Order\reoCategories;
use App\Models\Order\reoProducts;
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
        $accesories=reoCategories::where("slug","accessories")->first();

        factory(reoProducts::class)->create([
            "name"=>"Abelflex Plain",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Abelflex Adhesive",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Stiff Mastic Joints",
            "description"=>"Accessories",
            "category_id"=>$accesories->id
        ]);
    }
}
