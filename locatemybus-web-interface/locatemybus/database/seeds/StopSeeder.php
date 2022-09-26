<?php

use Illuminate\Database\Seeder;
use App\Stop;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Stop::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        Stop::create([
            'stop_id'=>'MAJ0091',
            'stop_name'=>'Majestic',
	    'latitude'=>12.9773452,
	    'longitude'=>77.5667917
        ]);
        Stop::create([
            'stop_id'=>'CVR0931',
            'stop_name'=>'CV Raman Nagar',
	    'latitude'=>12.9856267,
            'longitude'=>77.6618606
        ]);
        Stop::create([
            'stop_id'=>'HLS0271',
            'stop_name'=>'Halasuru',
	    'latitude'=>12.9856267,
            'longitude'=>77.6230861
        ]);
        Stop::create([
            'stop_id'=>'INR0371',
            'stop_name'=>'Indiranagar',
	    'latitude'=>12.9856267,
            'longitude'=>77.6230861
        ]);
    }
}
