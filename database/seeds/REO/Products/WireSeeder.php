<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class WireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $trench_mesh=orderReoCategory::where("slug","wire")->first();

        factory(orderReoProducts::class)->create([
            "name"=>"REINFORCEMENT TIE WIRES 80M Belt Pack 1.6",
            "description"=>"Wire",
            "category_id"=>$trench_mesh->id
        ]);
    }
}
