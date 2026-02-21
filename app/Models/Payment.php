<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'proof_of_payment',
        'status',
        'snap_token',
        'transaction_id',
        'payment_type',
        'gross_amount',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
