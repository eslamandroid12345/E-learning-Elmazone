<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapelSheetExamTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         مواعيد الامتحان الورقي (تضاف عند اضافه امتحان ورقي جديد)
         */
        Schema::create('papel_sheet_exam_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('from')->comment('موعد بدايه الامتحان الورقي بالقاعات');
            $table->time('to')->comment('موعد نهايه الامتحان الورقي بالقاعات');
            $table->unsignedBigInteger('papel_sheet_exam_id')->comment('رمز الامتحان الورقي');
            $table->timestamps();
            $table->foreign('papel_sheet_exam_id')->references('id')->on('papel_sheet_exams')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papel_sheet_exam_times');
    }
}
