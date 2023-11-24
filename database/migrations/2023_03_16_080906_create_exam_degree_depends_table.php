<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamDegreeDependsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول اعتماد درجات الامتحان الاونلاين والشامل
         */
        Schema::create('exam_degree_depends', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('timer_id')->comment('رقم المحاوله');
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب');
            $table->unsignedBigInteger('online_exam_id')->comment('رمز الامتحان الاونلاين')->nullable();
            $table->unsignedBigInteger('all_exam_id')->comment('رمز الامتحان الشامل')->nullable();
            $table->unsignedBigInteger('life_exam_id')->comment('رمز الامتحان الالايف')->nullable();
            $table->unsignedBigInteger('test_yourself_exam_id')->comment('رمز امتحان اختبر نفسك')->nullable();
            $table->integer('full_degree')->comment('مجموع الدرجات لهذه المحاوله');
            $table->enum('exam_depends',['yes','no'])->comment('هل الدرجه اعتمدت من قبل الطالب')->default('no');
            $table->timestamps();
            $table->foreign('timer_id')->references('id')->on('timers')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('all_exam_id')->references('id')->on('all_exams')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('exam_degree_depends');
    }
}
