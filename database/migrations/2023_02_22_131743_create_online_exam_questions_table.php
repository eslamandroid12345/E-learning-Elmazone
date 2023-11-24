<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineExamQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_exam_questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('online_exam_id')->comment('رمز الامتحان الاونلاين')->nullable();
            $table->unsignedBigInteger('all_exam_id')->comment('رمز الامتحان الشامل')->nullable();
            $table->unsignedBigInteger('life_exam_id')->comment('رمز الامتحان الالايف')->nullable();

            $table->unsignedBigInteger('question_id')->comment('رمز السؤال لهذا الامتحان');
            $table->timestamps();
            $table->foreign('all_exam_id')->references('id')->on('all_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('online_exam_id')->references('id')->on('online_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('question_id')->references('id')->on('questions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('life_exam_id')->references('id')->on('life_exams')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_exam_questions');
    }
}
