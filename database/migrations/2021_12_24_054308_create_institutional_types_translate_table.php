<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionalTypesTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutional_types_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('institutional_type_id');
            $table->foreign('institutional_type_id')->on('institutional_types')->references('id');

            $table->string("type");

            $table->string('lang_id');

            $table->string('active')->default('0');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');

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
        Schema::dropIfExists('institutional_types_translate');
    }
}
