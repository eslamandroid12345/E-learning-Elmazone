<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamsFavorite extends Model
{
    use HasFactory;

    protected $fillable = [
       'user_id',
       'online_exam_id',
       'all_exam_id',
        'life_exam_id',
       'action'
    ];
}
