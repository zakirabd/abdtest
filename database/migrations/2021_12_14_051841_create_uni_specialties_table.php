<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uni_specialties', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->foreign('specialty_id')->on('specialties')->references('id');
            $table->unsignedBigInteger('discipline_id')->nullable();
            $table->foreign('discipline_id')->on('disciplines')->references('id');
            $table->unsignedBigInteger('university_id')->nullable();
            $table->foreign('university_id')->on('education')->references('id');
            $table->string('fee_amount');
            $table->string('fee_currency_id')->nullable();
            $table->unsignedBigInteger('education_degree_id')->nullable();
            $table->foreign('education_degree_id')->on('education_degree')->references('id');
            $table->string('study_duration');
            $table->unsignedBigInteger('grading_scheme_id')->nullable();
            $table->foreign('grading_scheme_id')->on('grading_scheme')->references('id');
            $table->string('program_format');
            $table->string('start_date');
            $table->string('deadline');
            $table->string('gpa');
            $table->string('schoolarship_option');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->on('users')->references('id');
            $table->string('lang_id');
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
        Schema::dropIfExists('uni_specialties');
    }
}
