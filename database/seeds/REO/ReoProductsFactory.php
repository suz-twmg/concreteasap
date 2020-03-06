<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class ReoProductsFactory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reo=orderReoProducts::where("slug","")->first();
        //
        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL81",
            "description"=>"Mesh"
        ])->save(function($product) use($reo){
            $product->orders()->attach($reo);
        });

//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL62",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL72",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL82",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL92",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL102",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"Mesh 6mx2.4m SL102",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"UTE MESH 4mx2m SL62",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"UTE MESH 4mx2m SL72",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"UTE MESH 4mx2m SL82",
//            "description"=>"Mesh"
//        ]);
//
//        factory("App\Models\Order\orderReoProducts")->create([
//            "name"=>"UTE MESH 4mx2m SL82",
//            "description"=>"Mesh"
//        ]);
    }
}
