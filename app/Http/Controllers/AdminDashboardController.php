<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $totalBookingToday = Booking::whereDate('booking_date', $today)->count();
        
        $totalRevenue = Booking::whereIn('status', ['approved'])
            ->whereHas('payment', function($q) {
                $q->where('status', 'verified');
            })
            ->sum('total_price');
            
        $pendingVerification = Booking::where('status', 'pending')
            ->whereHas('payment', function($q) {
                $q->where('status', 'pending');
            })
            ->count();
            
        $totalCourts = Court::count();
        
        $latestBookings = Booking::with(['user', 'court', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        // Weekly Booking Stats
        $weeklyBookingData = [];
        $weeklyBookingDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyBookingDays[] = $date->translatedFormat('D');
            $weeklyBookingData[] = Booking::whereDate('booking_date', $date)->count();
        }

        return view('admin.dashboard', compact(
            'totalBookingToday',
            'totalRevenue',
            'pendingVerification',
            'totalCourts',
            'latestBookings',
            'weeklyBookingData',
            'weeklyBookingDays'
        ));
    }
}
