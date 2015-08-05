<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeerImg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beer_img', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('beer_id');
                $table->bigInteger('place_id');
                $table->string('img_id')->unique();
                $table->string('filename');
                $table->string('path');
                $table->text('description')->nullable();
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
