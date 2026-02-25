<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Membership;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'court', 'payment'])->latest()->get();
        $memberships = Membership::with(['user', 'tier', 'payment'])->latest()->get();
        return view('admin.bookings.index', compact('bookings', 'memberships'));
    }

    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'approved']);
        return back()->with('success', 'Booking berhasil disetujui.');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function verifyPayment(Payment $payment)
    {
        $payment->update(['status' => 'verified']);
        
        if ($payment->booking_id) {
            $payment->booking->update(['status' => 'approved']);
        } elseif ($payment->membership_id) {
            $membership = $payment->membership;
            $membership->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($membership->tier->duration_days),
            ]);
        }
        
        return back()->with('success', 'Pembayaran berhasil diverifikasi dan pesanan disetujui.');
    }

    public function rejectPayment(Payment $payment)
    {
        $payment->update(['status' => 'rejected']);

        if ($payment->booking_id) {
            $payment->booking->update(['status' => 'rejected']);
        } elseif ($payment->membership_id) {
            $payment->membership->update(['status' => 'rejected']);
        }

        return back()->with('success', 'Pembayaran ditolak dan pesanan dibatalkan.');
    }
}
