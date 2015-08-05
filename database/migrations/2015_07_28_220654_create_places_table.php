<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('place_id')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('latitude', 8,7);
            $table->decimal('longitude', 8,7);
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
