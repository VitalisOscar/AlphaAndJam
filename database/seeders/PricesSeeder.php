<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Screen;
use App\Models\ScreenPrice;
use Illuminate\Database\Seeder;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // packages
        $silver = Package::where('name', 'Silver')->first();
        $gold = Package::where('name', 'Gold')->first();
        $night = Package::where('name', 'Platinum')->first();

        $kimathi = Screen::where('title', 'Kimathi Street')->first();

        // ScreenPrice::create(['screen_id' => $kimathi->id, 'package_id' => $bronze->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $kimathi->id, 'package_id' => $silver->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $kimathi->id, 'package_id' => $gold->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $kimathi->id, 'package_id' => $night->id, 'price' => 100]);

        $hs = Screen::where('title', 'Haile Sellasie')->first();

        // ScreenPrice::create(['screen_id' => $hs->id, 'package_id' => $bronze->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $hs->id, 'package_id' => $silver->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $hs->id, 'package_id' => $gold->id, 'price' => 100]);
        ScreenPrice::create(['screen_id' => $hs->id, 'package_id' => $night->id, 'price' => 100]);
    }
}
