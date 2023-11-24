<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'month',
        'description_ar',
        'description_en',
        'from_id',
        'file',
        'icon',
        'file_type',
        'color',
        'background_color',
        'term_id',
        'season_id',
        'lesson_id',
        'subject_class_id',
        'answer_pdf_file',
        'answer_video_file',
        'answer_video_file',
    ];

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function childs()
    {
        return $this->hasMany(Guide::class,'from_id','id') ;
    }

    public function term(): BelongsTo
    {

        return $this->belongsTo(Term::class,'term_id','id');
    }

    public function season(): BelongsTo
    {

        return $this->belongsTo(Season::class,'season_id','id');
    }

    public function subjectClass(): BelongsTo
    {

        return $this->belongsTo(SubjectClass::class,'subject_class_id','id');
    }

    public function lesson(): BelongsTo
    {

        return $this->belongsTo(Lesson::class,'lesson_id','id');
    }

}
