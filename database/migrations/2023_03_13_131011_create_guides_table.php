<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         المصادر والمراجع
         */
        Schema::create('guides', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->integer('month')->nullable();
            $table->unsignedBigInteger('from_id')->nullable();

            $table->text('file')->nullable();
            $table->enum('file_type', ['video', 'pdf']);
            $table->text('answer_video_file')->nullable();
            $table->text('answer_pdf_file')->nullable();
            $table->text('icon')->nullable();
            $table->string('background_color')->nullable();
            $table->unsignedBigInteger('subject_class_id')->nullable();
            $table->unsignedBigInteger('lesson_id')->nullable();


            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
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
        Schema::dropIfExists('guides');
    }
}
