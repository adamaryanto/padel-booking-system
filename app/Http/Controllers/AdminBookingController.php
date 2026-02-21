<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'court', 'payment'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
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
        // Auto approve booking when payment verified? 
        // Let's keep it manual for now as per "Verifikasi pembayaran oleh admin"
        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function rejectPayment(Payment $payment)
    {
        $payment->update(['status' => 'rejected']);
        return back()->with('success', 'Pembayaran ditolak.');
    }
}
