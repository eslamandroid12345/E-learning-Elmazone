<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        /*
         جدول الخطه الشهريه للطالب
         */
        Schema::create('monthly_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('background_color')->comment('لون خلفيه الخطه الشهريه');
            $table->string('title_ar')->comment('عنوان الخطه باللغه العربيه');
            $table->string('title_en')->comment('عنوان الخطه باللغه الانجليزيه');
            $table->string('description_ar')->comment('وصف الخطه باللغه العربيه');
            $table->string('description_en')->comment('وصف الخطه باللغه الانجليزيه');
            $table->date('start')->comment('بدايه الخطه');
            $table->date('end')->comment('نهايه الخطه');
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
        Schema::dropIfExists('monthly_plans');
    }
}
