<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول اضافه الفيديوهات للمفضله
          فيديوهات الشرح وفيديوهات الاساسيات وفيديوهات المراجعه
         */
        Schema::create('video_favorites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('video_basic_id')->nullable();
            $table->unsignedBigInteger('video_resource_id')->nullable();
            $table->unsignedBigInteger('video_part_id')->nullable();
            $table->enum('action',['favorite','un_favorite']);
            $table->enum('favorite_type',['video_basic','video_resource','video_part']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_basic_id')->references('id')->on('video_basics')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_resource_id')->references('id')->on('video_resources')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('video_favorites');
    }
}
