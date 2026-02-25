<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_hour',
        'price_weekday',
        'price_weekend',
        'open_time',
        'close_time',
        'member_promo',
        'description',
        'facilities',
        'photo',
        'is_active',
    ];

    public function images()
    {
        return $this->hasMany(CourtImage::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
