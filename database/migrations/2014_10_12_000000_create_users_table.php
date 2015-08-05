<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('fb_id')->unique();
            $table->string('username')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->tinyInteger('username_flg');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('avatar');
            $table->string('gender  ');
            $table->string('provider');
            $table->string('provider_id');
            $table->integer('drink');
            $table->integer('pics');
            $table->integer('checkin');
            $table->integer('followers');
            $table->string('city');
            $table->string('country');
            $table->string('access_token');
            $table->string('access_token_secret');

            $table->rememberToken();
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
        Schema::drop('users');
    }
}
