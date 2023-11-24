<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TestYourSelfExamQuestions extends Model
{
    use HasFactory;

    public function test_exam(): BelongsToMany{

        return $this->belongsToMany(TestYourselfExams::class,'test_your_self_exam_questions', 'question_id','exam_id','id','id')
            ->withTimestamps();
    }


}
