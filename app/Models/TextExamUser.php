<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OnlineExam;

class TextExamUser extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'timer_id',
        'question_id',
        'online_exam_id',
        'all_exam_id',
        'answer',
        'image',
        'audio',
        'answer_type',
        'status',
        'degree',
        'degree_status'
    ];

    public function onlineExamId()
    {
        return $this->belongsTo(OnlineExam::class, 'online_exam_id', 'id');
    }



    public function question(){

        return $this->belongsTo(Question::class,'question_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }


}
