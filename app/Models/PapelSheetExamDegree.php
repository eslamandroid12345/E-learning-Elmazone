<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PapelSheetExamDegree extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }


    public function papel_sheet_exam(){

        return $this->belongsTo(PapelSheetExam::class,'papel_sheet_exam_id','id');
    }
}
