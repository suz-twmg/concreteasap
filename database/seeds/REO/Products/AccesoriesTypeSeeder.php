<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class AccesoriesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $accesories_type=orderReoCategory::where("slug","accessories")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);
    }
}
