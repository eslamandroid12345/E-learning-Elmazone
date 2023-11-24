<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function term(): BelongsTo
    {

        return $this->belongsTo(Term::class, 'term_id', 'id');
    }

    public function season(): BelongsTo
    {

        return $this->belongsTo(Season::class, 'season_id', 'id');
    }

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function notification_seen_student(): HasOne
    {
        return $this->hasOne(NotificationSeenStudent::class,'notification_id', 'id');

    }

}
