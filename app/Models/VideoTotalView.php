<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTotalView extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'video_basic_id',
        'video_resource_id',
        'video_part_id',
        'count'

    ];
}
