<?php

use App\Region;
use App\RegionType;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Region::truncate();

        $regionsData = $this->getRegionData();

        foreach ($regionsData as $region) {
            Region::insert($region);
        }
    }

    private function getRegionData()
    {
        $regionType = RegionType::where('name', 'Область')->first()->id;
        $districtType = RegionType::where('name', 'Район')->first()->id;
        
        $regions = require(app_path('./BarknestImport/regions.php'));

        return $regions;
    }
}
