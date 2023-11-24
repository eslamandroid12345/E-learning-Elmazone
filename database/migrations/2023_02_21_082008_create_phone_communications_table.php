<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhoneCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         هواتف السنتر
         */
        Schema::create('phone_communications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->comment('رقم الهاتف');
            $table->string('note')->comment('ملاحظات')->nullable();
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
        Schema::dropIfExists('phone_communications');
    }
}
