<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionTranslateApprovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_translate_approve', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institutions_translate_id');
            $table->foreign('institutions_translate_id')->on('institutions_translate')->references('id');

            $table->unsignedBigInteger('institutions_id');
            $table->foreign('institutions_id')->on('institutions')->references('id');

            $table->string("name");

            $table->string("description", '1000');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');

            $table->string("lang_id");

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
        Schema::dropIfExists('institution_translate_approve');
    }
}
