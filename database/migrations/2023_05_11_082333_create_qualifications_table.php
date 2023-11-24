<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         جدول مؤهلات المدرس
         */
        Schema::create('qualifications', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title_ar')->comment('العنوان باللغه العربيه');
            $table->text('title_en')->comment('العنوان باللغه الانجليزيه');
            $table->text('description_ar')->comment('وصف المهاره او الخبره او المؤهل باللغه العربيه');
            $table->text('description_en')->comment('وصف المهاره او الخبره او المؤهل باللغه الانجليزيه');
            $table->string('year')->comment('سنه الحصول علي هذه الخبره او المؤهل')->nullable();
            $table->enum('type',['qualifications','experience','skills']);
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
        Schema::dropIfExists('qualifications');
    }
}
