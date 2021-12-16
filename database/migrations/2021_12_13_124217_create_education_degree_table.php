<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationDegreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_degree', function (Blueprint $table) {
            $table->id();
            $table->string('education_type');
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
        Schema::dropIfExists('education_degree');
    }
}
