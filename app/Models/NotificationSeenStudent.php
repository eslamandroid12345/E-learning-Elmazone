<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSeenStudent extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','notification_id','seen'];

    public function user(): BelongsTo{

        return $this->belongsTo(User::class,'student_id','id');
    }


    public function notification(): BelongsTo{

        return $this->belongsTo(Notification::class,'notification_id','id');
    }


}
