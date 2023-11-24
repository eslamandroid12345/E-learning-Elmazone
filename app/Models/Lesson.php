<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'background_color',
        'title_ar',
        'title_en',
        'name_ar',
        'name_en',
        'note',
        'subject_class_id',
    ];

    /*
  |--------------------------------------------------------------------------
  | FUNCTIONS
  |--------------------------------------------------------------------------
  */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function exams(): MorphMany
    {
        return $this->morphMany(OnlineExam::class, 'examable');
    }

    public function questions(): MorphMany
    {
        return $this->morphMany(Question::class, 'examable')->where('question_type','=','choice');
    }


    public function subject_class(): BelongsTo{

        return $this->belongsTo(SubjectClass::class,'subject_class_id','id');
    }


    public function open_lessons(): HasMany{

        return $this->hasMany(OpenLesson::class,'lesson_id','id');
    }


    public function videos(): HasMany{

        return $this->hasMany(VideoParts::class,'lesson_id','id');
    }



}
