<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class VideoParts extends Model
{
    use HasFactory;


    protected $fillable = [
        'name_ar',
        'name_en',
        'month',
        'note',
        'lesson_id',
        'link',
        'background_image',
        'like_active',
        'youtube_link',
        'is_youtube',
        'video_time'

    ];

    //    public function exams()
    //    {
    //        return $this->morphMany(OnlineExam::class, 'examable');
    //    }


    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'video_part_id', 'id');
    }

    //start instruction for exams
    public function instruction(): MorphOne
    {
        return $this->morphOne(ExamInstruction::class, 'examable');
    }



    public function watches(): HasMany
    {
        return $this->hasMany(VideoOpened::class, 'video_part_id', 'id');
    }


    public function watch(): HasOne
    {
        return $this->hasOne(VideoOpened::class, 'video_part_id', 'id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }

    public function report(): HasMany
    {

        return $this->hasMany(Report::class,'video_part_id','id');
    }

    public function videoFileUpload(): HasMany
    {

        return $this->hasMany(VideoFilesUploads::class,'video_part_id','id');
    }


    public function rate(): HasMany
    {
        return $this->hasMany(VideoRate::class, 'video_id', 'id');
    }

    public function video_favorites(): HasMany
    {

        return $this->hasMany(VideoFavorite::class, 'video_part_id', 'id');
    }


    public function video_watches(): HasMany
    {

        return $this->hasMany(VideoOpened::class, 'video_part_id', 'id')
            ->where('status', '=', 'watched');
    }




}
