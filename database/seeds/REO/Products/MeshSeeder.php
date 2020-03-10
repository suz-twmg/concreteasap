<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use App\Models\Order\reoCategories;
use App\Models\Order\reoProducts;
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

        $reo=reoCategories::where("slug","mesh")->first();
        //
        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL81",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL62",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL72",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL92",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL102",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"Mesh 6mx2.4m SL102",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"UTE MESH 4mx2m SL62",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"UTE MESH 4mx2m SL72",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"UTE MESH 4mx2m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);

        factory(reoProducts::class)->create([
            "name"=>"UTE MESH 4mx2m SL82",
            "description"=>"Mesh",
            "category_id"=>$reo->id
        ]);
    }
}
