<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();
            
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $order_id = $notification->order_id; // Usually in format "BOOKING-{id}"
            $fraud = $notification->fraud_status;

            $bookingId = str_replace('BOOKING-', '', $order_id);
            $booking = Booking::find($bookingId);

            if (!$booking) {
                return response()->json(['message' => 'Booking not found'], 404);
            }

            $payment = Payment::where('booking_id', $booking->id)->first();

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $this->updateStatus($booking, $payment, 'pending', 'pending', $notification);
                    } else {
                        $this->updateStatus($booking, $payment, 'approved', 'verified', $notification);
                    }
                }
            } elseif ($transaction == 'settlement') {
                $this->updateStatus($booking, $payment, 'approved', 'verified', $notification);
            } elseif ($transaction == 'pending') {
                $this->updateStatus($booking, $payment, 'pending', 'pending', $notification);
            } elseif ($transaction == 'deny') {
                $this->updateStatus($booking, $payment, 'pending', 'rejected', $notification);
            } elseif ($transaction == 'expire') {
                $this->updateStatus($booking, $payment, 'pending', 'expired', $notification);
            } elseif ($transaction == 'cancel') {
                $this->updateStatus($booking, $payment, 'cancelled', 'cancelled', $notification);
            }

            return response()->json(['message' => 'Notification processed successfully']);
            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function updateStatus($booking, $payment, $bookingStatus, $paymentStatus, $notification)
    {
        $booking->update(['status' => $bookingStatus]);
        
        if ($payment) {
            $payment->update([
                'status' => $paymentStatus,
                'transaction_id' => $notification->transaction_id,
                'payment_type' => $notification->payment_type,
                'gross_amount' => $notification->gross_amount,
            ]);
        } else {
            Payment::create([
                'booking_id' => $booking->id,
                'status' => $paymentStatus,
                'transaction_id' => $notification->transaction_id,
                'payment_type' => $notification->payment_type,
                'gross_amount' => $notification->gross_amount,
            ]);
        }
    }
}
