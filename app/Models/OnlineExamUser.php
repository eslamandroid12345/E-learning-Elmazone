<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineExamUser extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function question(){

        return $this->belongsTo(Question::class,'question_id','id');
    }

    public function answer(){

        return $this->belongsTo(Answer::class,'answer_id','id');
    }

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }


    public function online_exam(){

        return $this->belongsTo(OnlineExam::class,'online_exam_id','id');
    }


    public function all_exam(){

        return $this->belongsTo(AllExam::class,'all_exam_id','id');
    }
}
