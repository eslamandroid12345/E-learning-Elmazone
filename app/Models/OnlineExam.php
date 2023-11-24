<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class OnlineExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_type',
        'title_result',
        'description_result',
        'image_result',
        'background_color',
        'pdf_file_upload',
        'pdf_num_questions',
        'answer_pdf_file',
        'answer_video_file',
        'answer_video_youtube',
        'answer_video_is_youtube',
        'name_ar',
        'name_en',
        'date_exam',
        'quize_minute',
        'trying_number',
        'degree',
        'type',
        'video_id',
        'lesson_id',
        'class_id',
        'term_id',
        'season_id',
        'instruction_ar',
        'instruction_en',
    ];

    protected $casts = [
        'instruction_ar' => 'json',
        'instruction_en' => 'json',

    ];



    public function season(): BelongsTo
    {

        return $this->belongsTo(Season::class,'season_id','id');
    }


    public function term(): BelongsTo
    {

        return $this->belongsTo(Term::class,'term_id','id');
    }

    public function instruction(): HasOne
    {

        return $this->hasOne(ExamInstruction::class,'online_exam_id', 'id');
    }

    public function questions(): BelongsToMany
    {

        return $this->belongsToMany(Question::class,'online_exam_questions', 'online_exam_id','question_id','id','id')->inRandomOrder();
    }




    public function video() :BelongsTo{

        return $this->belongsTo(VideoParts::class,'video_id','id');
    }

    public function exam_degree_depends(): HasMany{

        return $this->hasMany(ExamDegreeDepends::class,'online_exam_id','id');
    }




    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class,'lesson_id','id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SubjectClass::class,'class_id','id');
    }


    public function users(): BelongsToMany{

        return $this->belongsToMany(User::class,'online_exam_users','online_exam_id','user_id','id','id');

    }


    public function exams_favorites(): HasMany{

        return $this->hasMany(ExamsFavorite::class,'online_exam_id','id');
    }


    /*
    * start scopes
    */





    //start degree details
    public function scopeOnlineExamSubjectClassDegreeDetails($query,$class){

        return $query->where('class_id','=',$class->id)
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id)
            )->whereHas('exam_degree_depends', function ($q){
                $q->where('user_id','=', auth('user-api')->id())->where('exam_depends','=','yes');
            })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->get();
    }


    public function scopeOnlineExamSubjectClasses($query,$ids){

        return $query->whereIn('class_id',$ids)
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id)
            )->whereHas('exam_degree_depends', function ($q){
            $q->where('user_id','=', auth('user-api')->id())->where('exam_depends','=','yes');
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
        ->get();
    }

    public function scopeOnlineExamLessonVideosDegreeDetails($query,$lesson){

        return $query->whereHas('video', function ($q) use($lesson){
            $q->where('lesson_id','=', $lesson->id);
        })->whereHas('term', function ($term){
            $term->where('status', '=', 'active')->where('season_id','=',auth('user-api')->user()->season_id);
        })->whereHas('exam_degree_depends', function ($q){
            $q->where('user_id','=', auth('user-api')->id())->where('exam_depends','=','yes');
        })->where('season_id','=',auth()->guard('user-api')->user()->season_id)->get();
    }




    public function scopeOnlineExamLessonDegreeDetails($query,$lesson){

        return $query->where('lesson_id','=',$lesson->id)
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id))
            ->whereHas('exam_degree_depends',fn(Builder $builder)=>
            $builder->where('user_id','=', auth('user-api')->id())
                ->where('exam_depends','=','yes')
            )->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->get();
    }


    public function scopeOnlineExamAllLessons($query,$ids){

        return $query->whereIn('lesson_id',$ids)
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id))
            ->whereHas('exam_degree_depends',fn(Builder $builder)=>
            $builder->where('user_id','=', auth('user-api')->id())
                ->where('exam_depends','=','yes')
            )->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->get();
    }



    public function scopeOnlineExamLessons($query,$ids){

        return $query->whereIn('video_id',$ids)
            ->whereHas('term', fn(Builder $builder) =>
            $builder->where('status', '=', 'active')
                ->where('season_id','=',auth('user-api')->user()->season_id))
            ->whereHas('exam_degree_depends', function ($q){
                $q->where('user_id','=', auth('user-api')->id())
                    ->where('exam_depends','=','yes');
            })->where('season_id','=',auth()->guard('user-api')->user()->season_id)
            ->get();
    }

}
