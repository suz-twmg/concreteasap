<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class StarterBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $starter_bar=orderReoCategory::where("slug","starter_bar_mesh")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"R10 1000X200",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 1400X200",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 800X200",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 1000X200",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 1000X300",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 1200X150",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 1200X200",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N12 1200X250",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N16 1000X300",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"N16 1400X300",
            "description"=>"Starter bar",
            "category_id"=>$starter_bar->id
        ]);
    }
}
