<?php

namespace Database\Seeders;

use App\Models\Screen;
use Illuminate\Database\Seeder;

class ScreensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Screen::create(['title' => 'Kimathi Street', 'online' => true, 'slug' => 'kimathi-street']);
        Screen::create(['title' => 'Haile Sellasie', 'online' => true, 'slug' => 'haile-sellasie']);
    }
}
