<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class LifeExam extends Model
{
    use HasFactory;

    protected $fillable = [
       'name_ar',
       'name_en',
       'answer_video_file',
       'date_exam',
       'time_start',
       'time_end',
        'quiz_minute',
        'degree',
        'season_id',
        'term_id',
        'note',

    ];


    public function season(): BelongsTo{

        return $this->belongsTo(Season::class,'season_id','id');
    }


    public function term(): BelongsTo{

        return $this->belongsTo(Term::class,'term_id','id');
    }



    public function questions(): BelongsToMany{

        return $this->belongsToMany(Question::class,'online_exam_questions', 'life_exam_id','question_id','id','id')->inRandomOrder();
    }

    public function exams_favorites(): HasMany{

        return $this->hasMany(ExamsFavorite::class,'life_exam_id','id');
    }


    public function exams_degree_depends(): HasMany{

        return $this->hasMany(ExamDegreeDepends::class,'life_exam_id','id');
    }




}


