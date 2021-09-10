<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['name' => 'Fashion', 'slug' => 'fashion']);
        Category::create(['name' => 'Real Estate', 'slug' => 'real-estate']);
        Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        Category::create(['name' => 'Agricultural', 'slug' => 'agricultural']);
        Category::create(['name' => 'Others', 'slug' => 'others']);
    }
}
