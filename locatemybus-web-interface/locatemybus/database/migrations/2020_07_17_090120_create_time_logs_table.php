<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('trip_id');
	    $table->foreign('trip_id')
		  ->references('trip_id')
		  ->on('trips')
		  ->onDelete('no action')
		  ->onUpdate('no action');
	    $table->string('stop_id');
	    $table->foreign('stop_id')
		  ->references('stop_id')
		  ->on('stops')
		  ->onDelete('no action')
		  ->onUpdate('no action');
	    $table->date('arrival_date');
	    $table->time('arrival_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_logs');
    }
}
