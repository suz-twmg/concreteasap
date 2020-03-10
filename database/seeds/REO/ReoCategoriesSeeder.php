<?php

use App\Models\Order\reoCategories;
use Illuminate\Database\Seeder;

class ReoCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(reoCategories::class)->create([
            "name"=>"mesh",
            "slug"=>"mesh",
            "description"=>"Mesh"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Trench Mesh",
            "slug"=>"trench_mesh",
            "description"=>"Trench Mesh"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Stock bar",
            "slug"=>"stock_bar",
            "description"=>"Stock Bar"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Starter Bar Mesh",
            "slug"=>"starter_bar_mesh",
            "description"=>"Starter Bar Mesh"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Ligatures",
            "slug"=>"ligatures",
            "description"=>"Ligatures "
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Swimming Pool Reo",
            "slug"=>"swimming_pool_reo",
            "description"=>"Swimming Pool Reo"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Accessories",
            "slug"=>"accessories",
            "description"=>"Accessories"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Accessories Type",
            "slug"=>"accessories_type",
            "description"=>"Abelflex Plain"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Bar Chairs",
            "slug"=>"bar_chairs",
            "description"=>"Bar Chairs"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Plastic Membrane and Tape",
            "slug"=>"plastic_membrance",
            "description"=>"Swimming Pool Reo"
        ]);

        factory(reoCategories::class)->create([
            "name"=>"Wire",
            "slug"=>"wire",
            "description"=>"Wire"
        ]);
    }
}
