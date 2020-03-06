<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class MeshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $reo=orderReoCategory::where("slug","mesh")->first();
        //
        factory(orderReoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL81",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL62",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL72",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL92",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL102",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"Mesh 6mx2.4m SL102",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"UTE MESH 4mx2m SL62",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"UTE MESH 4mx2m SL72",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"UTE MESH 4mx2m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory("App\Models\Order\orderReoProducts")->create([
            "name"=>"UTE MESH 4mx2m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);
    }
}
