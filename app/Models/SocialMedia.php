<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
    use HasFactory;


    protected $fillable = [
        'facebook_link',
        'youtube_link',
        'instagram_link'

    ];

    //social media of teacher
}
