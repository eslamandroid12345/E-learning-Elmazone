<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         بنك الاسئله في المنصه
         */
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('question')->comment('نص السؤال');
            $table->enum('difficulty',['low','mid','high'])->comment('مستوي الصعوبه-سهل-متوسط-صعب');
            $table->enum('type',['video','lesson','all_exam','subject_class','life_exam'])->comment('السؤال لانهي قسم-فصول-دروس-فيديوهات-امتحان شامل-امتحان لايف');
            $table->longText('image')->comment('صوره مرفقه مع السؤال مثال رسومات')->nullable();
            $table->enum('file_type',['image','text','together'])->comment('السؤال-صوره-نص-كلاهما');
            $table->enum('question_type',['choice','text'])->comment('نوع السؤال-اختياري-مقالي');
            $table->integer('degree')->comment('درجه السؤال');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('term_id');
            $table->morphs('examable');
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
        Schema::dropIfExists('questions');
    }
}
