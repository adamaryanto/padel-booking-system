<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingService
{
    /**
     * Check field availability for a given court and date.
     */
    public function getAvailableSlots(int $courtId, string $date): array
    {
        $bookings = Booking::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where(function ($query) {
                $query->where('status', 'approved')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('created_at', '>=', now()->subMinutes(10));
                    });
            })
            ->get();

        $court = Court::find($courtId);
        
        // Use Carbon for robust time parsing
        $openTime = $court->open_time ? Carbon::parse($court->open_time) : Carbon::createFromTime(6, 0);
        $closeTime = $court->close_time ? Carbon::parse($court->close_time) : Carbon::createFromTime(22, 0);
        
        // If times are identical, they are likely placeholders or set incorrectly.
        // Fallback to defaults to ensure slots are shown.
        if ($openTime->equalTo($closeTime)) {
            $openTime = Carbon::createFromTime(6, 0);
            $closeTime = Carbon::createFromTime(22, 0);
        }

        $startHour = (int)$openTime->format('H');
        $endHour = (int)$closeTime->format('H');

        // Handle midnight edge case (e.g. 00:00 should be 24:00 for the loop)
        if ($endHour === 0) {
            $endHour = 24;
        }

        $todayStr = now()->format('Y-m-d');
        $isToday = $date === $todayStr;
        $currentTime = now();

        $slots = [];
        for ($i = $startHour; $i < $endHour; $i++) {
            $time = sprintf('%02d:00', $i);
            $isAvailable = true;

            // Check if time is in the past for today
            if ($isToday) {
                $slotTime = Carbon::parse($todayStr . ' ' . $time);
                if ($slotTime->lt($currentTime)) {
                    $isAvailable = false;
                }
            }

            if ($isAvailable) {
                foreach ($bookings as $booking) {
                    if ($time >= substr($booking->start_time, 0, 5) && $time < substr($booking->end_time, 0, 5)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            $slots[] = [
                'time' => $time,
                'available' => $isAvailable
            ];
        }

        return $slots;
    }

    /**
     * Check if a specific time range is available.
     */
    public function isTimeRangeAvailable(int $courtId, string $date, string $startTime, string $endTime): bool
    {
        // Prevent booking in the past
        if (Carbon::parse($date)->isToday()) {
            $startDateTime = Carbon::parse($date . ' ' . $startTime);
            if ($startDateTime->lt(now())) {
                return false;
            }
        }

        return !Booking::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where(function ($query) {
                $query->where('status', 'approved')
                    ->orWhere(function ($q) {
                        $q->where('status', 'pending')
                          ->where('created_at', '>=', now()->subMinutes(10));
                    });
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime) {
                    $q->where('start_time', '<=', $startTime)
                      ->where('end_time', '>', $startTime);
                })->orWhere(function ($q) use ($endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>=', $endTime);
                });
            })
            ->exists();
    }

    /**
     * Calculate booking price based on court rates and user membership.
     */
    public function calculatePricing(Court $court, string $date, int $duration, $user): array
    {
        $bookingDate = Carbon::parse($date);
        $isWeekend = $bookingDate->isWeekend();
        
        if ($isWeekend) {
            $basePrice = $court->price_weekend ?: $court->price_weekday ?: $court->price_per_hour;
        } else {
            $basePrice = $court->price_weekday ?: $court->price_weekend ?: $court->price_per_hour;
        }

        $originalPrice = $basePrice * $duration;
        $discountAmount = 0;
        $totalPrice = $originalPrice;

        $activeMembership = $user->activeMembership();
        if ($activeMembership) {
            $tier = $activeMembership->tier;
            $discountPercentage = $isWeekend ? ($tier->discount_weekend ?? $tier->discount_percentage) : ($tier->discount_weekday ?? $tier->discount_percentage);
            
            $discountAmount = $originalPrice * ($discountPercentage / 100);
            $totalPrice = $originalPrice - $discountAmount;
        }

        return [
            'original_price' => $originalPrice,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice
        ];
    }

    /**
     * Create a new booking.
     */
    public function createBooking(array $data, int $userId): Booking
    {
        $endTime = Carbon::parse($data['start_time'])->addHours((int) $data['duration']);
        
        return Booking::create([
            'user_id' => $userId,
            'court_id' => $data['court_id'],
            'booking_date' => $data['booking_date'],
            'start_time' => $data['start_time'],
            'end_time' => $endTime->format('H:i'),
            'total_price' => $data['total_price'],
            'original_price' => $data['original_price'],
            'discount_amount' => $data['discount_amount'],
            'status' => 'pending',
        ]);
    }
}
