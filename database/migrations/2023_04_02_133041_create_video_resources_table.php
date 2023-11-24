<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         فيديوهات المراجعه النهائيه لكل المراحل
         فيديوهات مراجعه او ملفات ورقيه
         */
        Schema::create('video_resources', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name_ar');
            $table->string('name_en');
            $table->longText('image')->comment('صوره خلفيه الفيديو');
            $table->string('background_color')->default('#48B8E0')->comment('لون خلفيه الملف');
            $table->integer('time')->nullable()->comment('زمن الفيديو');
            $table->longText('video_link')->nullable();
            $table->longText('youtube_link');
            $table->boolean('is_youtube')->default(0);
            $table->enum('type',['video','pdf'])->default('video');
            $table->longText('pdf_file')->nullable();
            $table->tinyInteger('like_active')->default(0);
            $table->tinyInteger('view_active')->default(0);
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
        Schema::dropIfExists('video_resources');
    }
}
