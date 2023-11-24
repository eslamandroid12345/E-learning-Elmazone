<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePapelSheetExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         الامتحانات الورقيه للطلبه بالقاعات
         */
        Schema::create('papel_sheet_exams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم الامتحان باللغه العربيه');
            $table->string('name_en')->comment('اسم الامتحان باللغه الانجليزيه');
            $table->integer('degree')->comment('درجه هذا الامتحان');
            $table->date('from')->comment('بدايه اتاحيه التسجيل في الامتحان الورقي');
            $table->date('to')->comment('تاريه نهايه الاتاحيه في التسجيل في الامتحان الورقي');
            $table->date('date_exam')->comment('موعد الامتحان في القاعات');
            $table->longText('description')->comment('وصف الامتحان-غير مطلوب')->nullable();
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('term_id');
            $table->timestamps();

            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('papel_sheet_exams');
    }
}
