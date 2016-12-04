<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditReviewsTable extends Migration
{
    /**
     * Run the migrations.
     * Add on delete cascade for reviews with resto_id foreign key.
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function(Blueprint $table)
		{
            $table->dropForeign('reviews_resto_id_foreign');
            $table->foreign('resto_id')->references('id')->on('restos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function(Blueprint $table)
		{
            $table->dropForeign('reviews_resto_id_foreign');
            $table->foreign('resto_id')->references('id')->on('restos');
        });
    }
}
