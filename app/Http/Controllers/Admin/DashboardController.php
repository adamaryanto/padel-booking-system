<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Membership;
use App\Models\Court;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Booking::where('status', 'approved')->sum('total_price');
        
        // Missing variables for dashboard boxes
        $totalBookingToday = Booking::whereDate('created_at', Carbon::today())->count();
        
        $courtRevenue = Booking::where('status', 'approved')->sum('total_price');
        
        $membershipRevenue = Payment::whereNotNull('membership_id')
            ->where('status', 'verified')
            ->sum('gross_amount');
            
        $monthlyTotalRevenue = Booking::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price') + 
            Payment::whereNotNull('membership_id')
            ->where('status', 'verified')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('gross_amount');
            
        $pendingVerification = Booking::where('status', 'pending')->count() + 
            Membership::where('status', 'pending')->count();
            
        $totalCourts = Court::where('is_active', true)->count();
        
        $latestBookings = Booking::with(['user', 'court'])->latest()->take(10)->get();

        // Monthly stats for chart
        $isSqlite = \DB::connection()->getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', created_at) AS INTEGER)" : "MONTH(created_at)";
        
        $monthlyBookings = Booking::selectRaw("COUNT(*) as count, $monthExpr as month")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $months = [];
        $bookingCounts = [];
        for ($m=1; $m<=12; $m++) {
            $months[] = Carbon::create(null, $m)->format('F');
            $found = $monthlyBookings->where('month', $m)->first();
            $bookingCounts[] = $found ? $found->count : 0;
        }

        // Weekly stats for ApexCharts
        $weeklyBookingRevenue = [];
        $weeklyMembershipRevenue = [];
        $weeklyBookingDays = [];
        
        $dayMap = [
            'Sun' => 'Min',
            'Mon' => 'Sen',
            'Tue' => 'Sel',
            'Wed' => 'Rab',
            'Thu' => 'Kam',
            'Fri' => 'Jum',
            'Sat' => 'Sab',
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyBookingDays[] = $dayMap[$date->format('D')];
            
            $weeklyBookingRevenue[] = Booking::where('status', 'approved')
                ->whereDate('created_at', $date)
                ->sum('total_price');
                
            $weeklyMembershipRevenue[] = Payment::whereNotNull('membership_id')
                ->where('status', 'verified')
                ->whereDate('created_at', $date)
                ->sum('gross_amount');
        }

        $pendingBookings = Booking::where('status', 'pending')
            ->whereHas('payment', function($q) {
                $q->where('status', 'pending');
            })
            ->count();
            
        $pendingMemberships = Membership::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalBookings', 
            'totalCustomers', 
            'totalRevenue', 
            'totalBookingToday',
            'courtRevenue',
            'membershipRevenue',
            'monthlyTotalRevenue',
            'pendingVerification',
            'totalCourts',
            'latestBookings',
            'weeklyBookingRevenue',
            'weeklyMembershipRevenue',
            'weeklyBookingDays',
            'pendingBookings',
            'pendingMemberships',
            'months',
            'bookingCounts'
        ));
    }
}
