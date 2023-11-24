<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        المدن
       */
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم المدينه بالعربي');
            $table->string('name_en')->comment('اسم المدينه بالانجليزي');
            $table->unsignedBigInteger('city_id')->comment('رمز المحافظه');
            $table->timestamps();
            $table->foreign('city_id')->references('id')->on('cities')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
