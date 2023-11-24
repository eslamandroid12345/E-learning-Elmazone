<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentReplaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول ردود الكومنتات
         */
        Schema::create('comment_replays', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->longText('comment')->comment('الرد علي التعليق')->nullable();
            $table->longText('audio')->comment('ملف صوتي')->nullable();
            $table->longText('image')->comment('صوره او ملف مرفق')->nullable();
            $table->enum('type',['text','audio','file'])->comment('النوع-نص-صوره-ملف صوتي');
            $table->unsignedBigInteger('student_id')->comment('رمز الطالب')->nullable();
            $table->unsignedBigInteger('teacher_id')->comment('رمز الاستاذ')->nullable();
            $table->unsignedBigInteger('comment_id')->comment('رمز التعليق');
            $table->enum('user_type',['student','teacher'])->comment('مين اللي رد-المدرس-ام الطالب')->default('student');
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('teacher_id')->references('id')->on('admins')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('comment_id')->references('id')->on('comments')->cascadeOnUpdate()->cascadeOnDelete();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_replays');
    }
}
