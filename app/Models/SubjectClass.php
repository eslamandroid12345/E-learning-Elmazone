<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Stripe\ApiOperations\All;

class SubjectClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'name_ar',
        'name_en',
        'note',
        'image',
        'background_color',
        'term_id',
        'season_id',
    ];


    public function lessons(): HasMany{

        return $this->hasMany(Lesson::class,'subject_class_id','id');
    }

    public function season(): BelongsTo{

        return $this->belongsTo(Season::class,'season_id','id');
    }


    public function term(): BelongsTo{

        return $this->belongsTo(Term::class,'term_id','id');
    }

//    public function exams()
//    {
//        return $this->morphMany(OnlineExam::class, 'examable');
//    }

    public function questions(): MorphMany
    {
        return $this->morphMany(Question::class, 'examable')->where('question_type','=','choice');
    }



    public function exams(){
        return $this->hasMany(OnlineExam::class,'class_id','id');
    }


    //start instruction for exams
    public function instruction(): MorphOne
    {
        return $this->morphOne(ExamInstruction::class, 'examable');
    }


    public function all_exams(){

        return $this->hasMany(AllExam::class,'subject_class_id','id')->whereHas('questions');
    }



    public function videos(): HasManyThrough{

        return $this->hasManyThrough(VideoParts::class,Lesson::class,'subject_class_id','lesson_id','id','id');
    }



}
