<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class VideoBasic extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'background_color',
        'time',
        'video_link',
        'youtube_link',
        'is_youtube',
    ];




    public function video_favorites(): HasMany{

        return $this->hasMany(VideoFavorite::class,'video_basic_id','id');
    }

    /*
   * start scopes
   */




    public function report(): HasMany
    {

        return $this->hasMany(Report::class,'video_basic_id','id');
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class, 'video_part_id', 'id');
    }

    /*
     * end scopes
     */

}
