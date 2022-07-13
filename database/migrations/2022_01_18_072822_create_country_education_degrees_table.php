<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryEducationDegreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_education_degree', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('countries_id');
            $table->foreign('countries_id')->on('countries')->references('id');


            $table->unsignedBigInteger('education_degree_id');
            $table->foreign('education_degree_id')->on('education_degree')->references('id');
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
        Schema::dropIfExists('country_education_degree');
    }
}
