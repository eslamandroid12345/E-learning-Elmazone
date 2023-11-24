<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         جدول الاشعارات بالتطبيق
          اشعارات بيضيفها المدرس لطالب معين يا اما لطلبه محددين يا اما لجميع الطلبه حسب الصف الدراسي والتيرم
          عند اضافه فيديو من نوع فيديو شرح او فيديو مراجعه او فيديو اساسيات
          او عند اضافه امتحان فصل او درس او واجب فيديو او امتحان شامل او امتحان لايف او امتحان ورقي بالقاعات
         */

        Schema::create('notifications', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('title');
            $table->text('body');
            $table->longText('image')->comment('صوره مرفقه')->nullable();
            $table->enum('user_type',['student','all_students','group_of_students'])->comment('طالب معين - مجموعه من الطلبه - جميع الطلبه');
            $table->unsignedBigInteger('user_id')->comment('رمز الطالب')->nullable();
            $table->json('group_ids')->comment('مجموعه الطلبه اللي اترسلهم اشعار')->nullable();
            $table->enum('notification_type',['text','video','exam'])->default('text')->comment('نوع الاشعار فيديو - امتحان - نص عادي');
            $table->enum('video_type',['video_basic','video_resource','video_part'])->nullable()->comment('نوع الفيديو يكون فيديو شرح - فيديو اساسيات - فيديو مراجعه');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->enum('exam_type',['subject_class','lesson','video','full_exam','life_exam','paper_sheet_exam'])->nullable()->comment('نوع الامتحان - امتحان فصل-درس-فيديو-شامل-لايف-امتحان ورقي');
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('season_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
