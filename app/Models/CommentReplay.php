<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReplay extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student(){

        return $this->belongsTo(User::class,'student_id','id');
    }


    public function teacher(){

        return $this->belongsTo(Admin::class,'teacher_id','id');
    }

    public function comment(){

        return $this->belongsTo(Comment::class,'comment_id','id');
    }
}
