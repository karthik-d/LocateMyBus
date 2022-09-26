<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_email')->unique()->nullable();
	    $table->foreign('user_email')
		  ->references('email')
		  ->on('users')
		  ->onDelete('cascade')
		  ->onUpdate('cascade');
	    $table->string('stop_id')->unique()->nullable();
	    $table->foreign('stop_id')
		  ->references('stop_id')
		  ->on('stops')
		  ->onDelete('cascade')
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
        Schema::dropIfExists('api_owners');
    }
}
