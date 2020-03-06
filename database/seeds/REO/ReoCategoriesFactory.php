<?php

use Illuminate\Database\Seeder;

class ReoCategoriesFactory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"mesh",
            "slug"=>"mesh",
            "description"=>"Mesh"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Trench Mesh",
            "slug"=>"trench_mesh",
            "description"=>"Trench Mesh"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Stock bar",
            "slug"=>"stock_bar",
            "description"=>"Stock Bar"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Starter Bar Mesh",
            "slug"=>"starter_bar_mesh",
            "description"=>"Starter Bar Mesh"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Ligatures",
            "slug"=>"ligatures",
            "description"=>"Ligatures "
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Swimming Pool Reo",
            "slug"=>"swimming_pool_reo",
            "description"=>"Swimming Pool Reo"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Accessories",
            "slug"=>"accessories",
            "description"=>"Accessories"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Accessories Type",
            "slug"=>"accessories_type",
            "description"=>"Abelflex Plain"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Expansion Joints",
            "slug"=>"expansion_joints",
            "description"=>"Expansion Joints"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Bar Chairs",
            "slug"=>"bar_chairs",
            "description"=>"Bar Chairs"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Plastic Membrane and Tape",
            "slug"=>"plastic_membrance",
            "description"=>"Swimming Pool Reo"
        ]);

        factory("App\Models\Order\orderReoCategory")->create([
            "name"=>"Wire",
            "slug"=>"wire",
            "description"=>"Wire"
        ]);
    }
}
