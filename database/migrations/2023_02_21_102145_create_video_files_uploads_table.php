<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoFilesUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_files_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم الملف الورقي او اسم الملف الصوتي باللغه العربيه');
            $table->string('name_en')->comment('اسم الملف الورقي او اسم الملف الصوتي باللغه الانجليزيه');
            $table->string('background_color')->comment('لون الخلفيه للملف الورقي او الملف الصوتي');
            $table->longText('file_link')->comment('لينك الملف الصوتي او الملف الورقي');
            $table->enum('file_type',['pdf','audio'])->comment('نوع الملف المرفق-ملف ورقي-ملف صوتي');
            $table->unsignedBigInteger('video_part_id')->comment('رمز فيديو الشرح');
            $table->foreign('video_part_id')->references('id')->on('video_parts')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('video_files_uploads');
    }
}
