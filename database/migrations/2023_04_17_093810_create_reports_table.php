<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول البلاغات للطلبه علي الفيديوهات (فيديوهات الشرح,فيديوهات الاساسيات,فيديوهات المراجعه)
         */
        Schema::create('reports', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->longText('report')->comment('بلاغ الطالب علي الفيديو');
            $table->unsignedBigInteger('user_id');
            $table->enum('type',['video_resource','video_basic','video_part'])->comment('فيديو شرح اساسيات او فيديو مراجعه او فيديو شرح');
            $table->unsignedBigInteger('video_part_id')->nullable();
            $table->unsignedBigInteger('video_basic_id')->nullable();
            $table->unsignedBigInteger('video_resource_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_part_id')->references('id')->on('video_parts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_basic_id')->references('id')->on('video_basics')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_resource_id')->references('id')->on('video_resources')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
