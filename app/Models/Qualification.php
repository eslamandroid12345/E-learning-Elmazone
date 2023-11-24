<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(mixed $id)
 * @method static create(array $array)
 */
class Qualification extends Model
{
    protected $fillable = [
        'type',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'year',
        'facebook_link',
        'youtube_link',
        'instagram_link'
    ];
}
