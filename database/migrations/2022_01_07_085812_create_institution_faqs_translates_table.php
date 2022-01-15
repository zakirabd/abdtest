<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionFaqsTranslatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_faqs_translate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institution_faqs_id');
            $table->foreign('institution_faqs_id')->on('institution_faqs')->references('id');
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
        Schema::dropIfExists('institution_faqs_translates');
    }
}
