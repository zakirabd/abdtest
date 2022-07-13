<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryEducationDegreeTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_education_degree_translate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_education_degree_id');
            $table->foreign('country_education_degree_id')->on('country_education_degree')->references('id');
            $table->string("name");
            $table->string("lang_id");
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
        Schema::dropIfExists('country_education_degree_translate');
    }
}
