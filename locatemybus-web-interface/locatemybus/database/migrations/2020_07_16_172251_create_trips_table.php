<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->string('trip_id')->primary();
            $table->integer('bus_id')
				->unsigned()
				->nullable();
			$table->foreign('bus_id')
				->references('bus_id')
				->on('buses')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->string('route_id');
			$table->foreign('route_id')
				->references('route_id')
				->on('routes')
				->onDelete('cascade')
				->onUpdate('cascade');
			$table->time('sched_start_time');
			$table->time('sched_end_time');
			$table->boolean('is_onward');
			$table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
