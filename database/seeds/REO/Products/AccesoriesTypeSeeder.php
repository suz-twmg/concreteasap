<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use App\Models\Order\reoCategories;
use App\Models\Order\reoProducts;
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
        $accesories_type=reoCategories::where("slug","accessories_type")->first();

        factory(reoProducts::class)->create([
            "name"=>"75MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"100MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"125MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"150MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"200MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"250MMX25M",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"2400MMX75MM",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"2400MMX100MM",
            "description"=>"Accessories",
            "category_id"=>$accesories_type->id
        ]);
    }
}
