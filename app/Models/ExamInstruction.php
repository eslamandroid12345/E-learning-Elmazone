<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamInstruction extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function all_exam(){

        return $this->belongsTo(AllExam::class,'all_exam_id','id');
    }

    public function online_exam(){

        return $this->belongsTo(OnlineExam::class,'online_exam_id','id');
    }




}
