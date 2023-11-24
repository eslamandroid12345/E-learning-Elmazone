<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoBasicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
          فيديوهات الاساسيات لكل المراحل
          */
        Schema::create('video_basics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('عنوان الفيديو باللغه العربيه');
            $table->string('name_en')->comment('عنوان الفيديو باللغه الانجليزيه');
            $table->string('background_color')->default('#48B8E0');
            $table->time('time')->comment('زمن الفيديو');
            $table->longText('video_link');
            $table->longText('youtube_link');
            $table->boolean('is_youtube')->default(0);
            $table->tinyInteger('like_active')->default(0);
            $table->tinyInteger('view_active')->default(0);
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
        Schema::dropIfExists('video_basics');
    }
}
