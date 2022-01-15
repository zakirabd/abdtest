<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisciplineFaqsTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discipline_faqs_translate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_faqs_id');
            $table->foreign('discipline_faqs_id')->on('discipline_faqs')->references('id');
            $table->string('question', '1000');
            $table->string('answer', '1000');
            $table->string('lang_id');
            $table->string('active');
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
        Schema::dropIfExists('discipline_faqs_translate');
    }
}
