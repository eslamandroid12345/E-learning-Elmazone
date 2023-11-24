<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class VideoResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'image',
        'background_color',
        'time',
        'video_link',
        'youtube_link',
        'is_youtube',
        'type',
        'pdf_file',
        'season_id',
        'like_active',
        'view_active',
        'term_id',

    ];

    public function season()
    {

        return $this->belongsTo(Season::class, 'season_id', 'id');
    }


    public function term()
    {

        return $this->belongsTo(Term::class, 'term_id', 'id');
    }

    public function video_favorites(): HasMany
    {

        return $this->hasMany(VideoFavorite::class, 'video_resource_id', 'id');
    }

    public function report(): HasMany
    {

        return $this->hasMany(Report::class, 'video_part_id', 'id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'video_resource_id', 'id');
    }




}
