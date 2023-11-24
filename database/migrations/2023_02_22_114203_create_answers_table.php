<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         اجابات الاسئله الاختياريه
         */
        Schema::create('answers', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('answer')->comment('الاجابه');
            $table->string('answer_number')->comment('ترقيم الاجابه')->nullable();
            $table->enum('answer_status',['correct','un_correct'])->default('correct')->comment('حاله الاجابه-صحيحه-غير صحيحه');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('question_id')->comment('رمز السؤال التابع لهذه الاجابه');
            $table->timestamps();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
