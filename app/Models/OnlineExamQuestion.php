<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlineExamQuestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function question(): BelongsTo
    {

        return $this->belongsTo(Question::class,'question_id','id');
    }

    public function online_exam(): BelongsTo
    {

        return $this->belongsTo(OnlineExam::class,'online_exam_id','id');
    }

    public function all_exam(): BelongsTo
    {

        return $this->belongsTo(AllExam::class,'all_exam_id','id');
    }

    public function live_exam(): BelongsTo
    {

        return $this->belongsTo(LifeExam::class,'life_exam_id','id');
    }
}
