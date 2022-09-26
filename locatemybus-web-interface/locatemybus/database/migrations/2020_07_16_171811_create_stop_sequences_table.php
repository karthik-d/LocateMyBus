<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopSequencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_sequences', function (Blueprint $table) {
           $table->increments('id');
           $table->string('stop_id');
           $table->foreign('stop_id')
                 ->references('stop_id')
                 ->on('stops')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
           $table->string('route_id');
           $table->foreign('route_id')
                 ->references('route_id')
                 ->on('routes')
                 ->onDelete('cascade')
                 ->onUpdate('cascade');
           $table->integer('onward_serial');
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stop_sequences');
    }
}
