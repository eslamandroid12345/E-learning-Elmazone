<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         تقييم فيديوهات الشرح وفيديوهات الاساسيات وفيديوهات المراجعه
         */
        Schema::create('video_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('video_basic_id')->nullable();
            $table->unsignedBigInteger('video_resource_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->enum('type',['video_part', 'video_basic','video_resource']);
            $table->enum('action',['like', 'dislike']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_id')->references('id')->on('video_parts')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('video_rates');
    }
}
