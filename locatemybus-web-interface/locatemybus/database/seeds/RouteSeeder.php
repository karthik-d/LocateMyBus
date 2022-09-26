<?php

use Illuminate\Database\Seeder;
use App\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      Route::truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      Route::create([
          'route_id'=>'314D',
          'origin'=>'MAJ0091',
          'destination'=>'CVR0931'
      ]);
    }
}
