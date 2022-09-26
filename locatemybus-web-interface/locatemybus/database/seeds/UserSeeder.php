<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	User::truncate();
	DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	$password = Hash::make('locatemybus_admin_access');
	$time = time();
	User::create([
	    'name'=>'Administrator',
	    'email'=>'admin@locatemybus.com',
	    'password'=>$password,
	    'created_at'=>$time
	]);
    }
}
