<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramExamSubsectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_exam_subsections', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->on('programs')->references('id');

            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')->on('exams')->references('id');

            $table->unsignedBigInteger('section_id')->nullable();
            $table->foreign('section_id')->on('exam_subsections')->references('id');

            $table->string('grade');

            $table->string('title');

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
        Schema::dropIfExists('program_exam_subsections');
    }
}
