<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_tier_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(MembershipTier::class, 'membership_tier_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'membership_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               $this->end_date && 
               $this->end_date->isAfter(now()->startOfDay());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->whereDate('end_date', '>=', now()->toDateString());
    }
}
