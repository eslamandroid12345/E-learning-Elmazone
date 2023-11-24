<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'difficulty',
        'type',
        'image',
        'file_type',
        'question_type',
        'degree',
        'note',
        'season_id',
        'term_id',

    ];

    public function answers(): HasMany{

        return $this->hasMany(Answer::class,'question_id','id')->inRandomOrder();
    }


    public function season(): BelongsTo{

        return $this->belongsTo(Season::class,'season_id','id');
    }


    public function term(): BelongsTo{

        return $this->belongsTo(Term::class,'term_id','id');
    }


    //start relations of exams (relation many to many)

    public function online_exams(): BelongsToMany{

        return $this->belongsToMany(OnlineExam::class,'online_exam_questions', 'question_id','online_exam_id','id','id');
    }

    public function all_exams(): BelongsToMany{

        return $this->belongsToMany(OnlineExam::class,'online_exam_questions', 'question_id','all_exam_id','id','id');
    }


    public function life_exams(): BelongsToMany{

        return $this->belongsToMany(OnlineExam::class,'online_exam_questions', 'question_id','life_exam_id','id','id');
    }



}
