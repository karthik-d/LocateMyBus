<?php

use Illuminate\Database\Seeder;
use App\StopSequence;

class StopSequenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      StopSequence::truncate();
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      StopSequence::create([
          'stop_id'=>'MAJ0091',
          'route_id'=>'314D',
          'onward_serial'=>'1'
      ]);
      StopSequence::create([
          'stop_id'=>'CVR0931',
          'route_id'=>'314D',
          'onward_serial'=>'4'
      ]);
      StopSequence::create([
          'stop_id'=>'HLS0271',
          'route_id'=>'314D',
          'onward_serial'=>'2'
      ]);
      StopSequence::create([
          'stop_id'=>'INR0371',
          'route_id'=>'314D',
          'onward_serial'=>'3'
      ]);
    }
}
