<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         فصول الماده
         */
        Schema::create('subject_classes', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('title_ar')->comment('عنوان الفصل باللغه العربيه مثال الفصل الاول');
                $table->string('title_en');
                $table->string('name_ar')->comment('اسم الفصل باللغه العربيه مثال المقاومه الكهربيه');
                $table->string('name_en');
                $table->string('note');
                $table->longText('image')->comment('صوره معبره عن هذا الفصل')->nullable();
                $table->string('background_color')->comment('لون خلفيه هذا الفصل');
                $table->unsignedBigInteger('season_id')->comment('رمز الفصل الدراسي')->nullable();
                $table->unsignedBigInteger('term_id')->comment('رمز التيرم التابع له هذا الفصل');
                $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('subject_classes');
    }
}
