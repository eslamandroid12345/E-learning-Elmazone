<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenLesson extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'lesson_id',
        'subject_class_id',
        'status',
    ];
}
