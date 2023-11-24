<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         فيديوهات الشرح علي كل درس
         */
        Schema::create('video_parts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم فيديو الشرح باللغه العربيه');
            $table->string('name_en')->comment('اسم فيديو الشرح باللغه الانجليزيه');
            $table->boolean('is_youtube')->default(0)->comment('هل نوع الفيديو لينك يوتيوب');
            $table->string('background_image')->comment('صوره خلفيه الفيديو');
            $table->integer('month')->comment('محتوي انهي شهر في السنه');
            $table->unsignedBigInteger('lesson_id')->comment('فيديو انهي درس');
            $table->longText('link')->comment('لينك الفيديو في حاله رفع فيديو للمنصه');
            $table->longText('youtube_link')->comment('لينك الفيديو في حاله استدعاء لينك خارجي');
            $table->tinyInteger('like_active')->default(0);
            $table->tinyInteger('view_active')->default(0);
            $table->time('video_time');
            $table->text('note')->comment('ملاحظات غير مطلوبه')->nullable();
            $table->timestamps();
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_parts');
    }
}
