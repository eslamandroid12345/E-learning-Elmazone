<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'video_basic_id',
        'video_resource_id',
        'video_part_id',
        'action',
        'favorite_type'

    ];


    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }
}
