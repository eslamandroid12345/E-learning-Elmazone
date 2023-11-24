<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon',
        'discount_type',
        'discount_amount',
        'valid_from',
        'valid_to',
        'is_enabled',
        'total_usage',

    ];
}
