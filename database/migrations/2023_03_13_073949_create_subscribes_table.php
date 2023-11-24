<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول اشتراكات الشهور للفصول الدراسيه
         */
        Schema::create('subscribes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('price_in_center',10,2);
            $table->double('price_out_center',10,2);
            $table->integer('month');
            $table->enum('free',['yes','no'])->default('no');
            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('subscribes');
    }
}
