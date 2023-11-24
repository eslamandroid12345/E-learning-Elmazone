<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول المحاولات للطلبه
         */
        Schema::create('timers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('online_exam_id')->comment('رمز الامتحان الاونلاين')->nullable();
            $table->unsignedBigInteger('all_exam_id')->comment('رمز الامتحان الشامل')->nullable();
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب');
            $table->string("timer")->comment('عدد دقائق هذه المحاوله');
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('all_exam_id')->references('id')->on('all_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('timers');
    }
}
