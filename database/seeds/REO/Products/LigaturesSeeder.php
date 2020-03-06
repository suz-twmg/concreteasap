<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class LigaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $ligatures=orderReoCategory::where("slug","ligatures")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"R6 350X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R6 400X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R6 500X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R6 350X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R6 400X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R6 500X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 350X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 400X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 500X200MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 350X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 400X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"R10 500X300MM",
            "description"=>"Ligatures",
            "category_id"=>$ligatures->id
        ]);
    }
}
