<?php

namespace Database\Seeders;

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
        $this->call(CategoriesSeeder::class);
        $this->call(ScreensSeeder::class);
        $this->call(PackagesSeeder::class);
        $this->call(PricesSeeder::class);
        $this->call(StaffSeeder::class);
    }
}
