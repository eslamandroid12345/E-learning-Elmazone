<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول العد التنازلي للامتحان لجميع الصفوف الدراسيه
         */
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('image')->comment('صوره العد التنازلي للامتحان')->nullable();
            $table->string('title_ar')->comment('عنوان العد التنازلي-مثال-نصيحه هامه');
            $table->string('title_en')->comment('عنوان العد التنازلي-مثال-Important advice');
            $table->text('description_ar')->comment('وصف العد التنازلي باللغه العربيه');
            $table->text('description_en')->comment('وصف العد التنازلي باللغه الانجليزيه');
            $table->timestamp('date_time')->comment('موعد وتاريخ الامتحان');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('season_id');
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('exam_schedules');
    }
}
