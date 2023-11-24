<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('teacher_name_ar')->comment('اسم المدرس بالعربي');
            $table->string('teacher_name_en')->comment('اسم المدرس بالانجليزي');
            $table->string('department_ar')->comment('اسم التخصص بالعربي');
            $table->string('department_en')->comment('اسم التخصص بالانجليزي');
            $table->longText('teacher_image')->comment('صوره المدرس')->nullable();
            $table->text('facebook_link')->comment('لينك فيس بوك التطبيق');
            $table->text('whatsapp_link')->comment('لينك واتساب التطبيق');
            $table->text('youtube_link')->comment('لينك يوتيوب التطبيق');
            $table->text('twitter_link')->comment('لينك التواصل عبر تويتر')->nullable();
            $table->text('instagram_link')->comment('لينك التواصل عبر انستجرام')->nullable();
            $table->text('website_link')->comment('لينك موقع التطبيق')->nullable();
            $table->text('sms')->nullable();
            $table->text('messenger')->nullable();
            $table->text('facebook_personal')->comment('فيس بوك الاستاذ')->nullable();
            $table->text('youtube_personal')->comment('يوتيوب الاستاذ')->nullable();
            $table->text('instagram_personal')->comment('انستجرام الاستاذ')->nullable();
            $table->json('share_ar');
            $table->json('share_en');
            $table->enum('lang',['not_active', 'active'])->comment('تفعيل اللغه بالتطبيق')->default('not_active');
            $table->enum('videos_resource_active',['not_active', 'active'])->comment('تفعيل فيديوهات المراجعه النهائيه')->default('not_active');
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
        Schema::dropIfExists('settings');
    }

    protected $casts = [

        'share_ar' => 'json',
        'share_en' => 'json',

    ];
}
