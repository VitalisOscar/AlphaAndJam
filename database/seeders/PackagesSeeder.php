<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create([
            'name' => 'Gold',
            'plays_from' => 6,
            'plays_to' => 9,
            'clients' => 15,
            'loops' => 50,
            'type' => 'peak'
        ]);

        Package::create([
            'name' => 'Silver',
            'plays_from' => 9,
            'plays_to' => 16,
            'clients' => 15,
            'loops' => 50,
            'type' => 'off_peak'
        ]);



        Package::create([
            'name' => 'Platinum',
            'plays_from' => 16,
            'plays_to' => 21,
            'clients' => 15,
            'loops' => 50,
            'type' => 'peak'
        ]);
    }
}
