<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class PlasticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $plastic=orderReoCategory::where("slug","plastic_membrance")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"Polytheme Building Film premium orange 200um 25M long 4M wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"Polytheme Building Film standard black 200um 50M long 4M wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"TAPE 50m x 48mm wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

    }
}
