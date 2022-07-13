<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->on('countries')->references('id');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->on('states')->references('id');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->on('cities')->references('id');



            $table->unsignedBigInteger('institution_id');
            $table->foreign('institution_id')->on('institutions')->references('id');

            $table->string('fee_amount');

            $table->string('fee_currency_id');

            $table->unsignedBigInteger('education_degree_id');
            $table->foreign('education_degree_id')->on('education_degree')->references('id');

            $table->string('education_language_id');

            $table->string('study_duration');

            $table->string('start_date');

            $table->string('deadline');

            $table->string('gpa');

            $table->string('schoolarship_option')->nullable();

            $table->string('video_link')->nullable();

            $table->string('active')->default('0');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');

            $table->string('ib_diploma')->nullable();

            $table->string('a_level')->nullable();

            $table->string('advanced_placement')->nullable();

            $table->string('ossd')->nullable();

            $table->string('application_fee')->nullable();

            $table->string('fee_type')->nullable();

            $table->string('schoolarship_type')->nullable();

            $table->string('local_exam')->default('0');

            $table->string('required_education_level')->nullable();

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
        Schema::dropIfExists('programs');
    }
}
