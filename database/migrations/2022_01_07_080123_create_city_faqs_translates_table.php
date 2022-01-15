<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityFaqsTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_faqs_translate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_faqs_id');
            $table->foreign('city_faqs_id')->on('city_faqs')->references('id');
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
        Schema::dropIfExists('city_faqs_translates');
    }
}
