<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Membership;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Get or generate Snap Token for a Booking.
     */
    public function getBookingSnapToken(Booking $booking, $user): ?string
    {
        $payment = Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'status' => 'pending',
                'gross_amount' => $booking->total_price,
            ]
        );

        if ($payment->snap_token) {
            return $payment->snap_token;
        }

        $orderId = 'BOOKING-' . $booking->id . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'BOOKING-' . $booking->id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Pembayaran Booking PadelHub',
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.finish'),
                'error' => route('payment.finish'),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->update([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Error (Booking): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get or generate Snap Token for a Membership.
     */
    public function getMembershipSnapToken(Membership $membership, $user): ?string
    {
        $payment = Payment::firstOrCreate(
            ['membership_id' => $membership->id],
            [
                'status' => 'pending',
                'gross_amount' => $membership->tier->price,
            ]
        );

        if ($payment->snap_token) {
            return $payment->snap_token;
        }

        $orderId = 'MEMBERSHIP-' . $membership->id . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $membership->tier->price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'MEMBERSHIP-' . $membership->id,
                    'price' => (int) $membership->tier->price,
                    'quantity' => 1,
                    'name' => 'Pembayaran Membership PadelHub',
                ]
            ],
            'callbacks' => [
                'finish' => route('dashboard'),
                'unfinish' => route('dashboard'),
                'error' => route('dashboard'),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->update([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error("Midtrans Snap Error (Membership): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync payment status from Midtrans.
     */
    public function syncTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            $transaction = $status->transaction_status;
            $type = $status->payment_type;
            $fraud = $status->fraud_status;

            if (str_starts_with($orderId, 'BOOKING-')) {
                $parts = explode('-', $orderId);
                $id = $parts[1]; // Get the booking ID
                $booking = Booking::with('payment')->find($id);
                if ($booking && ($booking->status === 'pending' || $booking->status === 'unpaid')) {
                    // Double check if this is the latest order_id for this payment
                    if ($booking->payment && ($booking->payment->order_id === $orderId || $booking->payment->order_id === null)) {
                        $this->updateBookingStatus($booking, $status);
                    }
                }
            } elseif (str_starts_with($orderId, 'MEMBERSHIP-')) {
                $parts = explode('-', $orderId);
                $id = $parts[1]; // Get the membership ID
                $membership = Membership::with('payment')->find($id);
                if ($membership && ($membership->status === 'pending' || $membership->status === 'cancelled')) {
                    // Double check if this is the latest order_id for this payment
                    if ($membership->payment && ($membership->payment->order_id === $orderId || $membership->payment->order_id === null)) {
                        $this->updateMembershipStatus($membership, $status);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("Transaction status sync failed for $orderId: " . $e->getMessage());
        }
    }

    private function updateBookingStatus(Booking $booking, $status)
    {
        $transaction = $status->transaction_status;
        if ($transaction == 'capture' || $transaction == 'settlement') {
            $booking->update(['status' => 'approved']);
            $booking->payment->update([
                'status' => 'verified',
                'transaction_id' => $status->transaction_id,
                'payment_type' => $status->payment_type,
                'gross_amount' => $status->gross_amount,
            ]);
        } elseif ($transaction == 'expire' || $transaction == 'cancel' || $transaction == 'deny') {
            $booking->payment->update(['status' => $transaction]);
        }
    }

    public function updateMembershipStatus(Membership $membership, $status)
    {
        $transaction = $status->transaction_status;
        if ($transaction == 'capture' || $transaction == 'settlement') {
            $membership->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($membership->tier->duration_days),
            ]);
            $membership->payment->update([
                'status' => 'verified',
                'transaction_id' => $status->transaction_id,
                'payment_type' => $status->payment_type,
                'gross_amount' => $status->gross_amount,
            ]);
        } elseif ($transaction == 'expire' || $transaction == 'cancel' || $transaction == 'deny') {
            $membership->payment->update(['status' => $transaction]);
        }
    }

    /**
     * Expire stale pending records older than 10 minutes.
     */
    public function expireStalePayments()
    {
        $expiryTime = now()->subMinutes(10);

        Booking::where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->update(['status' => 'expired']);

        Membership::where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->update(['status' => 'expired']);

        Payment::where('status', 'pending')
            ->where('created_at', '<', $expiryTime)
            ->update(['status' => 'expired']);
    }
}
