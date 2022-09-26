<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->increments('bus_id');
            $table->string('model');
	    	$table->string('route_id')
		 		->nullable();
	    	$table->foreign('route_id')
                ->references('route_id')
		  		->on('routes')
		  		->onDelete('set null')
		  		->onUpdate('cascade');
			$table->string('rf_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses');
    }
}
