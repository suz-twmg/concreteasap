<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class TrenchMeshSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $trench_mesh=orderReoCategory::where("slug","trench_mesh")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"L8TM200",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L8TM300",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L8TM400",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L8TM500",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L11TM200",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L11TM300",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L11TM400",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L11TM500",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L12TM200",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);

        factory(orderReoProducts::class)->create([
            "name"=>"L12TM300",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);
        factory(orderReoProducts::class)->create([
            "name"=>"L12TM400",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);
        factory(orderReoProducts::class)->create([
            "name"=>"L12TM500",
            "description"=>"Mesh",
            "category_id"=>$trench_mesh->id
        ]);
    }
}





