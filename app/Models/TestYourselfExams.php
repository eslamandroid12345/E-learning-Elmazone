<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestYourselfExams extends Model{


    use HasFactory;
    protected $fillable = [
        'questions_type',
        'total_degree',
        'user_id',
        'lesson_id',
        'subject_class_id',
        'total_time',
        'num_of_questions',
    ];


    public function user(): BelongsTo{

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function lesson(): BelongsTo{

        return $this->belongsTo(Lesson::class,'lesson_id','id');
    }

    public function subject_class(): BelongsTo{

        return $this->belongsTo(SubjectClass::class,'subject_class_id','id');
    }


    public function questions(): BelongsToMany{

        return $this->belongsToMany(Question::class,'test_your_self_exam_questions', 'exam_id','question_id','id','id')
            ->withTimestamps();
    }




}
