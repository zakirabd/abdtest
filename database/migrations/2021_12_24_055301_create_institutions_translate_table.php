<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('institutions_id');
            $table->foreign('institutions_id')->on('institutions')->references('id');

            $table->string("name");

            $table->string("description", '1000');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id');

            $table->string("lang_id");

            $table->string('active')->default('0');

            $table->string('approve_status')->default('0');

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
        Schema::dropIfExists('institutions_translate');
    }
}
