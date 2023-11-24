<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextExamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
          جدول اجابات الاسئله المقاليه للطالب
         */

        Schema::create('text_exam_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('timer_id')->comment('رقم المحاوله');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('online_exam_id')->nullable();
            $table->unsignedBigInteger('all_exam_id')->nullable();
            $table->longText('answer')->nullable();
            $table->longText('image')->nullable();
            $table->text('audio')->nullable();
            $table->enum('answer_type',['text','file','audio']);
            $table->integer('degree')->default(0);
            $table->enum('degree_status',['completed','un_completed']);
            $table->enum('status',['solved','leave']);
            $table->timestamps();
            $table->foreign('timer_id')->references('id')->on('timers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('all_exam_id')->references('id')->on('all_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('text_exam_users');
    }
}
