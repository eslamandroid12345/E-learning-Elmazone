<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestYourselfExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول اختبر نفسل
          يقوم الطالب بصنع امتحان او اختبار لنفسه ويقوم بوضع عدد من الاسئله المتنوعه لهذا الامتحان ويقوم باداء هذا الامتحان داخل التطبيق
         */
        Schema::create('test_yourself_exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('questions_type',array('low','mid','high'));
            $table->integer('total_degree');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id')->nullable();
            $table->unsignedBigInteger('subject_class_id')->nullable();
            $table->integer('total_time');
            $table->integer('num_of_questions');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('subject_class_id')->references('id')->on('subject_classes')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_yourself_exams');
    }
}
