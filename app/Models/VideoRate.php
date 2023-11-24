<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoRate extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','video_id','video_basic_id','video_resource_id','type','action'];

    public function user(): BelongsTo{

        return $this->belongsTo(User::class,'user_id','id');
    }

    public function video(): BelongsTo{

        return $this->belongsTo(VideoParts::class,'video_id','id');
    }


    public function video_basic(): BelongsTo{

        return $this->belongsTo(VideoBasic::class,'video_basic_id','id');
    }


    public function video_resource(): BelongsTo{

        return $this->belongsTo(VideoResource::class,'video_resource_id','id');
    }
}
