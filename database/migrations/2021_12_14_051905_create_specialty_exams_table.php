<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtyExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialty_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->foreign('specialty_id')->on('uni_specialties')->references('id');
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->foreign('exam_id')->on('exams')->references('id');
            $table->string('over_all');
            $table->string('end_date');
            $table->string('section_1')->nullable();;
            $table->string('section_2')->nullable();;
            $table->string('section_3')->nullable();;
            $table->string('section_4')->nullable();;
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
        Schema::dropIfExists('specialty_exams');
    }
}
