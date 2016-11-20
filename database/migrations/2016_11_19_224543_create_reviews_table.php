<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reviews');
		Schema::create('reviews', function(Blueprint $table){
			$table->increments('id');
			$table->string('title');
            $table->string('content');
            $table->integer('rating')->unsigned();
			$table->timestamps();
            //foreign key for user
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
				  ->references('id')->on('users');
            //foreign key for resto
            $table->integer('resto_id')->unsigned();
			$table->foreign('resto_id')
				  ->references('id')->on('restos');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
