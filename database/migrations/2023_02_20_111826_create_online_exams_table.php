<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*
         الامتحانات الاونلاين لثلاث اقسام (فصل,درس,فيديو شرح)
         */
        Schema::create('online_exams', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name_ar')->comment('اسم الامتحان الاونلاين باللغه العربيه');
            $table->string('name_en')->comment('اسم الامتحان الاونلاين باللغه الانجليزيه');
            $table->string('title_result')->comment('عنوان النصيحه عند نهايه الامتحان');
            $table->string('description_result')->comment('وصف النصيحه للطلب عند نهايه الامتحان');
            $table->string('image_result')->comment('صوره مرفقه مع النصيحه للطلب عند نهايه الامتحان');
            $table->string('background_color')->comment('لون خلفيه هذا الامتحان');
            $table->enum('exam_type',['online','pdf'])->comment('نوع الامتحان-اونلاين-ملف ورقي');
            $table->longText('pdf_file_upload')->comment('الملف الورقي المرفق عندما يكون النوع ملف ورقي');
            $table->longText('pdf_num_questions')->comment('عدد اسئله الامتحان الورقي-عندما يكون النوع ملف ورقي');
            $table->longText('answer_pdf_file')->comment('ملف الاجابه الورقي-عندما يكون النوع ملف ورقي');
            $table->longText('answer_video_file')->comment('فيديو الاجابه لهذا الامتحان');
            $table->longText('answer_video_youtube')->comment('لينك فيديو يوتيوب في حاله عدم رفع فيديو علي السيرفر');
            $table->boolean('answer_video_is_youtube')->default(0)->comment('هل اجابه الامتحان فيديو عادي ام فيديو يوتيوب');
            $table->string('date_exam')->comment('تاريخ اضافه الامتحان');
            $table->integer('quize_minute')->comment('عدد دقائق هذا الامتحان	');
            $table->integer('trying_number')->comment('عدد المحاولات لهذا الامتحان للطلبه');
            $table->integer('degree')->comment('درجه هذا الامتحان');
            $table->enum('type',['class','lesson','video'])->comment('نوع القسم');

            $table->unsignedBigInteger('class_id')->nullable()->comment('النوع فصل في حاله اختيار قسم الفصول');
            $table->unsignedBigInteger('lesson_id')->nullable()->comment('النوع درس في حاله اختيار قسم الدروس');
            $table->unsignedBigInteger('video_id')->nullable()->comment('رمز الفيديو في حاله قسم الفيديوهات');

            $table->unsignedBigInteger('season_id')->comment('الفصل الدراسي');
            $table->unsignedBigInteger('term_id')->comment('التيرم');
            $table->json('instruction_ar')->comment('التعليمات لهذا الامتحان باللغه العربيه-في حاله النوع امتحان شامل');
            $table->json('instruction_en')->comment('التعليمات لهذا الامتحان باللغه الانجليزيه-في حاله النوع امتحان شامل');
            $table->timestamps();

            $table->foreign('season_id')->references('id')->on('seasons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('term_id')->references('id')->on('terms')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('class_id')->references('id')->on('subject_classes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('lesson_id')->references('id')->on('lessons')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('video_id')->references('id')->on('video_parts')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('online_exams');
    }
}
