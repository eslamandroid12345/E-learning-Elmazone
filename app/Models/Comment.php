<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function video(){

        return $this->belongsTo(VideoParts::class,'video_part_id','id');
    }

    public function replays(){

        return $this->hasMany(CommentReplay::class,'comment_id','id');
    }
}
