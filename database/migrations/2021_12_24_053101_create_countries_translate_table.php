<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('countries_id');
            $table->foreign('countries_id')->on('countries')->references('id');

            $table->string('name');
            $table->string('description');

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
        Schema::dropIfExists('countries_translate');
    }
}
