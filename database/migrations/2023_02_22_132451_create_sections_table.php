<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         قاعات الامتحانات الورقيه
         */
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('section_name_ar')->comment('اسم القاعه باللغه العربيه');
            $table->string('section_name_en')->comment('اسم القاعه باللغه الانجليزيه');
            $table->longText('address')->comment('عنوان القاعه');
            $table->integer('capacity')->comment('سعه القاعه');
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
        Schema::dropIfExists('sections');
    }
}
