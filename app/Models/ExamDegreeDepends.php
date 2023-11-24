<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamDegreeDepends extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'timer_id',
        'online_exam_id',
        'all_exam_id',
        'full_degree',
        'exam_depends',
        'life_exam_id',
        'test_yourself_exam_id'
    ];

    public function online_exam(): BelongsTo{

        return $this->belongsTo(OnlineExam::class,'online_exam_id','id');
    }

    public function life_exam(): BelongsTo
    {
        return $this->belongsTo(LifeExam::class,'life_exam_id','id');
    }

    public function all_exam(): BelongsTo{

        return $this->belongsTo(AllExam::class,'all_exam_id','id');
    }


    public function user(): BelongsTo{

        return $this->belongsTo(User::class,'user_id','id');
    }




}
