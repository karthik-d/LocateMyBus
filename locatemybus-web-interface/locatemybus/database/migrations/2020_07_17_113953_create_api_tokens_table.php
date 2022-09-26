<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateApiTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_tokens', function (Blueprint $table) {
            $table->string('api_token')->primary();
	    $table->string('access_type'); //Specifies access privilege
            $table->string('owner_type');  //Specifies the owner type of API Token
	    $table->integer('owner_id')->unsigned();
	    $table->foreign('owner_id')
	          ->references('id')
		  ->on('api_owners')
		  ->onDelete('cascade')
		  ->onUpdate('cascade');
	    $table->date('expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_tokens');
    }
}
