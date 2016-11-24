<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('restos');
		Schema::create('restos', function(Blueprint $table){
			$table->increments('id');
			$table->string('name');
            $table->string('genre');
			$table->string('price');
            $table->string('address');
            $table->decimal('latitude', 10, 6);	
            $table->decimal('longitude', 10, 6);
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				  ->references('id')->on('users');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restos');
    }
}
