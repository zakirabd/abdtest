<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('image');
            $table->string('active')->default('1');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->on('countries')->references('id');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->on('states')->references('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->string('lang_id');
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
        Schema::dropIfExists('cities');
    }
}
