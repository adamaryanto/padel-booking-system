<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'discount_percentage',
        'discount_weekday',
        'discount_weekend',
        'duration_days',
        'booking_window_days',
        'description',
        'is_active',
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
}
