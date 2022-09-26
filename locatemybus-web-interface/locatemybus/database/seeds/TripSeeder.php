<?php

use Illuminate\Database\Seeder;
use App\Trip;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		Trip::truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		Trip::create([
			'trip_id'=>'314D0915O',  //RouteID+StartTime+O(nward)/R(eturn)
			//'bus_id'=>1,
			'route_id'=>'314D',
			'sched_start_time'=>'09:15',
			'sched_end_time'=>'11:45',
			'is_onward'=>1,
			'is_active'=>1
		]);
		Trip::create([
			'trip_id'=>'314D1330O',  //RouteID+StartPlace+O(nward)/R(eturn)
			//'bus_id'=>2,
			'route_id'=>'314D',
			'sched_start_time'=>'13:30',
			'sched_end_time'=>'15:15',
			'is_onward'=>1,
			'is_active'=>1
		]);
		Trip::create([
			'trip_id'=>'314D1200R',  //RouteID+StartTime+O(nward)/R(eturn)
			//'bus_id'=>1,
			'route_id'=>'314D',
			'sched_start_time'=>'12:00',
			'sched_end_time'=>'14:50',
			'is_onward'=>0,
			'is_active'=>1
		]);
		Trip::create([
			'trip_id'=>'314D1600R',  //RouteID+StartPlace+O(nward)/R(eturn)
			//'bus_id'=>2,
			'route_id'=>'314D',
			'sched_start_time'=>'16:00',
			'sched_end_time'=>'18:10',
			'is_onward'=>0,
			'is_active'=>1
		]);
    }
}
