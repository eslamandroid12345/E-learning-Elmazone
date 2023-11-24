<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoOpenedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول فتح فيديوهات الشرح بالمرفقات للطالب
         */
        Schema::create('video_opened', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب');
            $table->unsignedBigInteger('video_part_id')->comment('رمز فيديو الشرح');
            $table->unsignedBigInteger('video_upload_file_pdf_id')->comment('الملف الورقي التابع لهذا الفيديو');
            $table->unsignedBigInteger('video_upload_file_audio_id')->comment('الملف الصوتي التابع لهذا الفيديو');
            $table->enum('status',['opened','watched'])->comment('حاله المشاهده في حاله سماع 65 % الحاله تتغير ل watched');
            $table->enum('type',['video','audio','pdf'])->comment('نوع الملف فيديو او ملف ورقي او ملف صوتي');
            $table->time('minutes')->comment('عدد الدقائق اللي سمعها الطالب لهذا الفيديو');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_part_id')->references('id')->on('video_parts')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_watches');
    }
}
