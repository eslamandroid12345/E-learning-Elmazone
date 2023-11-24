<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         دروس الفصول بالماده
         */
        Schema::create('lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('background_color')->comment('لون خلفيه هذا الدرس');
            $table->string('title_ar')->comment('عنوان الدرس باللغه العربيه مثال الدرس الاول');
            $table->string('title_en');
            $table->string('name_ar')->comment('اسم الدرس ياللغه العربيه');
            $table->string('name_en');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('subject_class_id')->comment('رمز الفصل او الوحده التابع له هذا الدرس');
            $table->foreign('subject_class_id')->references('id')->on('subject_classes')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('lessons');
    }
}
