<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtyLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialty_language', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->foreign('specialty_id')->on('uni_specialties')->references('id');
            $table->unsignedBigInteger('language_id')->nullable();
            $table->foreign('language_id')->on('education_language')->references('id');
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
        Schema::dropIfExists('specialty_language');
    }
}
