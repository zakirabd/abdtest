<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountyFagsTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('county_fags_translates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_fags_id');
            $table->foreign('country_fags_id')->on('country_fags')->references('id');
            $table->string('question', '1000');
            $table->string('answer', '1000');
            $table->string('lang_id');
            $table->string('active');
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
        Schema::dropIfExists('county_fags_translates');
    }
}
