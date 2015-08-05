<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeersListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('beer');
            $table->string('slug')->unique();
            $table->string('logo');
            $table->integer('brewery_id');
            $table->integer('style_id');
            $table->decimal('abv', 5, 2)->default(0);
            $table->decimal('scores', 5, 1)->default(0);
            $table->integer('votes');
            $table->integer('views');
            $table->integer('drink');
            $table->integer('like');
            $table->integer('want');
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
        Schema::drop('beers');
    }
}
