<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveTrafficPredictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_traffic_predictions', function (Blueprint $table) {
            $table->increments('id');
            $table->string("trip_id");
	    $table->foreign("trip_id")
		  ->references("trip_id")
		  ->on("trips")
		  ->onDelete("cascade")
		  ->onUpdate("cascade");
	    $table->date("trip_date");
	    $table->time("predicted_time");
	    $table->string("stop_id");
	    $table->foreign("stop_id")
	    	  ->references("stop_id")
		  ->on("stops")
		  ->onDelete("cascade")
		  ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_traffic_predictions');
    }
}
