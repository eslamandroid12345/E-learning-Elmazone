<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoFilesUploads extends Model
{
    use HasFactory;

    protected $fillable = [

        'name_ar',
        'name_en',
        'background_color',
        'file_link',
        'file_type',
        'video_part_id'
    ];

    public function video_part(): BelongsTo{

        return $this->belongsTo(VideoParts::class,'video_part_id','id');
    }
}
