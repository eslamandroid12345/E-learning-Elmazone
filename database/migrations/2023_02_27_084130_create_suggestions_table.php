<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول الاقتراحات للطالب
         */
        Schema::create('suggestions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('suggestion')->nullable();
            $table->longText('image')->nullable();
            $table->text('audio')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('type',['text','audio','file']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suggestions');
    }
}
