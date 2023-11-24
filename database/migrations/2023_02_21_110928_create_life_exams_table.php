<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLifeExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         الامتحانات الالايف
         */

        Schema::create('life_exams', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم الامتحان الالايف باللغه العربيه');
            $table->string('name_en')->comment('اسم الامتحان الالايف باللغه الانجليزيه');
            $table->date('date_exam')->comment('تاريخ اداء الامتحان');
            $table->time('time_start')->comment('موعد بدايه الامتحان الالايف');
            $table->time('time_end')->comment('موعد نهايه الامتحان الالايف');
            $table->integer('quiz_minute')->comment('عدد دقائق الامتحان');
            $table->integer('degree')->comment('درجه هذا الامتحان');
            $table->unsignedBigInteger('season_id')->comment('الفصل الدراسي');
            $table->unsignedBigInteger('term_id')->comment('التيرم');
            $table->text('note')->nullable();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('life_exams');
    }
}
