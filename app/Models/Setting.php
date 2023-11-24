<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_image',
        'teacher_name_ar',
        'teacher_name_en',
        'department_ar',
        'department_en',
        'lang',
        'facebook_link',
        'whatsapp_link',
        'youtube_link',
        'twitter_link',
        'instagram_link',
        'website_link',
        'sms',
        'messenger',
        'share_ar',
        'share_en',
        'videos_resource_active',
        'facebook_personal',
        'youtube_personal',
        'instagram_personal',

    ];

    protected $casts = [
        'share_ar' => 'json',
        'share_en' => 'json',
    ];

}
