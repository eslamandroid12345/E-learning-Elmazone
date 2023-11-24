<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCouponStudent extends Model
{
    use HasFactory;

    protected $fillable = [
      'discount_coupon_id',
        'user_id'

    ];

    public function student(): BelongsTo{

        return $this->belongsTo(User::class,'user_id','id');
    }
}
