<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivationalSentencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول الجمل التحفيزيه
         */
        Schema::create('motivational_sentences', function (Blueprint $table) {
            $table->id();
            $table->text('title_ar');
            $table->text('title_en');
            $table->tinyInteger('percentage_from');
            $table->tinyInteger('percentage_to');
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
        Schema::dropIfExists('motivational_sentences');
    }
}
