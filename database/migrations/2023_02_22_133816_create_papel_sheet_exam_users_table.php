<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapelSheetExamUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         تسجيل الطلبه بالامتحان الورقي وتوزيعهم مباشر علي القاعات
         */
        Schema::create('papel_sheet_exam_users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب');
            $table->unsignedBigInteger('section_id')->comment('رمز القاعه');
            $table->unsignedBigInteger('papel_sheet_exam_id')->comment('رمز الامتحان الورقي');
            $table->unsignedBigInteger('papel_sheet_exam_time_id')->comment('رمز موعد الامتحان الورقي');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('papel_sheet_exam_id')->references('id')->on('papel_sheet_exams')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('papel_sheet_exam_time_id')->references('id')->on('papel_sheet_exam_times')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papel_sheet_exam_users');
    }
}
