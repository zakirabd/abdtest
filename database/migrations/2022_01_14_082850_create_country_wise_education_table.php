<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryWiseEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_wise_education', function (Blueprint $table) {
            $table->id();

            $table->string('residental_country_id');
            $table->string('residental_degree_id');
            $table->string('residental_sub_degree_id');
            $table->string('destination_country_id');
            $table->string('destination_degree_id');

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
        Schema::dropIfExists('country_wise_education');
    }
}
