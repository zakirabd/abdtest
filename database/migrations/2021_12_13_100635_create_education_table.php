<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->on('institutional_types')->references('id');
            $table->string("ranking")->nullable();
            $table->string("international_ranking")->nullable();
            $table->string("name");
            $table->string("title");
            $table->string("description");
            $table->string("logo")->nullable();
            $table->string("image")->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->on('cities')->references('id');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->on('countries')->references('id');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->on('states')->references('id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->string("lang_id");
            $table->string('active')->default('1');
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
        Schema::dropIfExists('education');
    }
}
