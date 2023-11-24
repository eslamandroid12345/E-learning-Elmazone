<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSubscribesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول اشتراكات الطلاب
         */
        Schema::create('user_subscribes', function (Blueprint $table) {
            $table->id();
            $table->double('price',10,2);
            $table->integer('month');
            $table->string('year');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscribes');
    }
}
