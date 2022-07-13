<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->on('cities')->references('id');

            $table->string('name');

            $table->string('description', '1000');

            $table->string('lang_id');

            $table->string('active')->default('0');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');

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
        Schema::dropIfExists('cities_translate');
    }
}
