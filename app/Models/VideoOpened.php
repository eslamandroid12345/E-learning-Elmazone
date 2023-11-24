<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoOpened extends Model
{
    use HasFactory;

    protected  $guarded = [];

    protected $table = 'video_opened';


    public function video(): BelongsTo{
        return $this->belongsTo(VideoParts::class, 'video_part_id','id');
    }


    public function user():BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id','id');
    }


}
