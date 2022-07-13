<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentEducationBackgroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_education_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->on('users')->references('id');
            $table->string('study_country_id');
            $table->string('study_degree_id');
            $table->string('study_sub_degree_id')->nullable();
            $table->string('grading_scheme_id')->nullable();
            $table->string('study_gpa');
            $table->string('institution_name')->nullable();
            $table->string('study_language')->nullable();
            $table->string('attended_institution_from')->nullable();
            $table->string('attended_institution_to')->nullable();
            $table->string('degree_award')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('student_education_backgrounds');
    }
}
