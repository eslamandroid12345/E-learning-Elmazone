<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Report extends Model
{
    use HasFactory;

    protected $fillable = [

        'report',
        'user_id',
        'type',
        'video_part_id',
        'video_basic_id',
        'video_resource_id',
    ];



    //Start relation eloquent models

    public function user(){

        return $this->belongsTo(User::class,'user_id','id');
    }


    public function video_part(): BelongsTo
    {

        return $this->belongsTo(VideoParts::class,'video_part_id','id');
    }


    public function video_basic(): BelongsTo
    {

        return $this->belongsTo(VideoBasic::class,'video_basic_id','id');
    }


    public function video_resource(): BelongsTo
    {

        return $this->belongsTo(VideoResource::class,'video_resource_id','id');
    }

}


