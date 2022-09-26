<?php

use Illuminate\Database\Seeder;
use App\Bus;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 60891530
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      Bus::truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      Bus::create([
          'route_id'=>'314D',
          'model'=>'MBenz',
		  'rf_id'=>'961372148204'
      ]);
      Bus::create([
          'route_id'=>'314D',
          'model'=>'Volvo',
		  "rf_id"=>'AAAD1286'  // Dummy
      ]);
    }
}
