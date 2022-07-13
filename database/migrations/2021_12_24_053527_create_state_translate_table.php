<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStateTranslateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('state_translate', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->on('states')->references('id');

            $table->string('name');

            $table->string('description', '1000');

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
        Schema::dropIfExists('state_translate');
    }
}
