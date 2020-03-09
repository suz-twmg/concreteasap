<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //I need to merge this functionality
        $this->call(UsersTableSeeder::class);
        $this->call(ReoCategoriesSeeder::class);
        $this->call(ReoProductsSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
