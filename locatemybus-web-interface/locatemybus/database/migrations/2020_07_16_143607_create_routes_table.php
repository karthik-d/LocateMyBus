<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
	    $table->increments('id');
            $table->string('route_id')->unique();
            $table->string('origin');
	    $table->foreign('origin')
	   	  ->references('stop_id')
		  ->on('stops') // stops cannot be directly deleted.
		  ->onUpdate('cascade');
            $table->string('destination');
	    $table->foreign('destination') 
                  ->references('stop_id')
                  ->on('stops')
		  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routes');
    }
}