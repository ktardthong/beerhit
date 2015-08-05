<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeerRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beer_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('beer_id');
            $table->text('comment');
            $table->decimal('taste', 5, 2);
            $table->decimal('look', 5, 2);
            $table->decimal('smell', 5, 2);
            $table->decimal('feel', 5, 2);
            $table->decimal('overall', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
