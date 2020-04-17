<?php

use App\RegionType;
use Illuminate\Database\Seeder;

class RegionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RegionType::truncate();

        RegionType::insert([
            ["name" => "Страна"],
            ["name" => "Область"],
            ["name" => "Район"]
        ]);
    }
}