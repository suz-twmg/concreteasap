<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use App\Models\Order\reoProducts;
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
        $plastic=\App\Models\Order\reoCategories::where("slug","plastic_membrance")->first();

        factory(reoProducts::class)->create([
            "name"=>"Polytheme Building Film premium orange 200um 25M long 4M wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Polytheme Building Film standard black 200um 50M long 4M wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"TAPE 50m x 48mm wide",
            "description"=>"Plastic",
            "category_id"=>$plastic->id
        ]);

    }
}
