<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineExamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         اجابات الطلبه للامتحانات
         */
        Schema::create('online_exam_users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('timer_id')->comment('رقم المحاوله');
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب');
            $table->unsignedBigInteger('question_id')->comment('رمز السؤال');
            $table->unsignedBigInteger('answer_id')->comment('الاجابه اللي اختارها للسؤال')->nullable();
            $table->unsignedBigInteger('online_exam_id')->comment('رمز الامتحان الاونلاين')->nullable();
            $table->unsignedBigInteger('all_exam_id')->comment('رمز الامتحان الشامل')->nullable();
            $table->unsignedBigInteger('life_exam_id')->comment('رمز الامتحان الايف')->nullable();
            $table->unsignedBigInteger('test_yourself_exam_id')->comment('رمز امتحان اختبر نفسك')->nullable();
            $table->integer('degree')->comment('الدرجه المحصل عليها')->default(0);

            $table->enum('status',['solved','leave','un_correct'])->comment('حاله السؤال-محلول-مغادر-غير صحيح');
            $table->timestamps();
            $table->foreign('timer_id')->references('id')->on('timers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('answer_id')->references('id')->on('answers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('all_exam_id')->references('id')->on('all_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('life_exam_id')->references('id')->on('life_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('test_yourself_exam_id')->references('id')->on('test_yourself_exams')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_exam_users');
    }
}
