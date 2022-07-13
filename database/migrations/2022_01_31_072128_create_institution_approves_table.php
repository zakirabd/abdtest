<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_approve', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institutions_id')->nullable();
            $table->foreign('institutions_id')->on('institutions')->references('id');

            $table->unsignedBigInteger('type')->nullable();
            $table->foreign('type')->on('institutional_types')->references('id');

            $table->string("national_ranking")->nullable();

            $table->string("international_ranking")->nullable();


            $table->string("logo")->nullable();

            $table->string("background_image")->nullable();

            $table->string("image")->nullable();

            $table->string("video_link")->nullable();

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->on('cities')->references('id');

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->on('countries')->references('id');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->on('states')->references('id');

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
        Schema::dropIfExists('institution_approve');
    }
}
