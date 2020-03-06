<?php

use App\Models\Order\orderReoCategory;
use App\Models\Order\orderReoProducts;
use Illuminate\Database\Seeder;

class ReoProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccesoriesSeeder::class);
        $this->call(AccesoriesTypeSeeder::class);
        $this->call(LigaturesSeeder::class);
        $this->call(MeshSeeder::class);
        $this->call(PlasticSeeder::class);
        $this->call(StarterBarSeeder::class);
        $this->call(StockBarSeeder::class);
        $this->call(SwimmingPoolSeeder::class);
        $this->call(TrenchMeshSeeder::class);
        $this->call(WireSeeder::class);
    }
}
