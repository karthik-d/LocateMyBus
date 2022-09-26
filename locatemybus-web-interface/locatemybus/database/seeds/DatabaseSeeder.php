<?php

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
      $this->call(UserSeeder::class);
		$this->call(StopSeeder::class);
      $this->call(RouteSeeder::class);
      $this->call(BusSeeder::class);
      $this->call(StopSequenceSeeder::class);
      $this->call(TripSeeder::class);
    }
}
