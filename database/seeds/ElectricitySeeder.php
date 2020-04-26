<?php

use App\Electricity;
use Illuminate\Database\Seeder;

class ElectricitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Electricity::truncate();
        
        $electricities = $this->getElectricities();
        
        $electricityChunks = array_chunk($electricities, 100);

        foreach ($electricityChunks as $chunk) {
            $readyChunk = $this->adaptFields($chunk);
            Electricity::insert($readyChunk);
        }
        
    }

    private function getElectricities()
    {
        return require(app_path('./BarknestImport/electricity.php'));
    }

    private function adaptFields($electricities)
    {
        $adapted = [];

        foreach ($electricities as $electricity) {
            $adapted[] = [
                'id' => $electricity['id'],
                'region_id' => $electricity['id_region'],
                'hours' => $electricity['hours'],
                'date' => $electricity['date'],
                'comment' => $electricity['comment'],
                'day_period' => $electricity['d_period'],
                'night_period' => $electricity['n_period'],
                'created_at' => $electricity['cdate'],
                'updated_at' => $electricity['cdate']
            ];
        }
        return $adapted;
    }
}
