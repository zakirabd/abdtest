<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationLanguageTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_language_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('education_language_id');
            $table->foreign('education_language_id')->on('education_language')->references('id');

            $table->string('language');

            $table->string('lang_id');

            $table->string('active')->default('0');

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
        Schema::dropIfExists('education_language_translate');
    }
}
