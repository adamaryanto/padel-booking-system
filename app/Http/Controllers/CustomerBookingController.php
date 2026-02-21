<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class CustomerBookingController extends Controller
{
    public function index()
    {
        $courts = Court::where('is_active', true)->get();
        $landingContent = \App\Models\LandingPageContent::first() ?? new \App\Models\LandingPageContent([
            'hero_title' => 'LOCKED PEEK PERFORMANCE',
            'hero_subtitle' => 'ULTIMATE PADEL EXPERIENCE',
            'hero_cta_text' => 'BOOK ARENA',
            'hero_cta_link' => '#courts',
            'about_title' => 'The Future of Padel is Here',
            'about_subtitle' => 'THE ARENA',
            'about_description' => 'Kami menghadirkan standar baru dalam dunia Padel di Indonesia.',
        ]);
        
        return view('welcome', compact('courts', 'landingContent'));
    }

    public function show(Court $court)
    {
        return view('customer.courts.show', compact('court'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1|max:5',
        ]);

        $startTime = Carbon::parse($request->start_time);
        $endTime = (clone $startTime)->addHours((int) $request->duration);
        $court = Court::find($request->court_id);

        // Check for conflicts
        $conflict = Booking::where('court_id', $request->court_id)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request, $endTime) {
                $query->where(function ($q) use ($request) {
                    $q->where('start_time', '<=', $request->start_time)
                      ->where('end_time', '>', $request->start_time);
                })->orWhere(function ($q) use ($endTime) {
                    $q->where('start_time', '<', $endTime->format('H:i'))
                      ->where('end_time', '>=', $endTime->format('H:i'));
                });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'Jadwal yang Anda pilih sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $totalPrice = $court->price_per_hour * $request->duration;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'court_id' => $request->court_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i'),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Generate snap token for immediate payment
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'status' => 'pending',
            'gross_amount' => $booking->total_price,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => 'BOOKING-' . $booking->id,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $payment->update(['snap_token' => $snapToken]);
            return redirect()->route('dashboard', ['pay' => $snapToken])->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            \Log::error("Midtrans Snap Error on Store: " . $e->getMessage());
            return redirect()->route('dashboard')->with('success', 'Booking berhasil dibuat. Gagal menghasilkan token pembayaran otomatis, silakan bayar melalui dashboard.');
        }
    }

    public function dashboard()
    {
        $bookings = Booking::with(['court', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Ensure snap tokens for pending bookings
        foreach ($bookings as $booking) {
            if ($booking->status === 'pending' && (!$booking->payment || !$booking->payment->snap_token)) {
                $payment = Payment::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'status' => 'pending',
                        'gross_amount' => $booking->total_price,
                    ]
                );

                if (!$payment->snap_token) {
                    Config::$serverKey = config('midtrans.server_key');
                    Config::$isProduction = config('midtrans.is_production');
                    Config::$isSanitized = config('midtrans.is_sanitized');
                    Config::$is3ds = config('midtrans.is_3ds');

                    $params = [
                        'transaction_details' => [
                            'order_id' => 'BOOKING-' . $booking->id,
                            'gross_amount' => (int) $booking->total_price,
                        ],
                        'customer_details' => [
                            'first_name' => Auth::user()->name,
                            'email' => Auth::user()->email,
                        ],
                    ];

                    try {
                        $snapToken = Snap::getSnapToken($params);
                        $payment->update(['snap_token' => $snapToken]);
                    } catch (\Exception $e) {
                        // Log or handle error quietly for dashboard
                        \Log::error("Midtrans Snap Error on Dashboard: " . $e->getMessage());
                    }
                }
            }
        }

        return view('dashboard', compact('bookings'));
    }

    public function payment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $payment = Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'status' => 'pending',
                'gross_amount' => $booking->total_price,
            ]
        );

        if (!$payment->snap_token) {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => 'BOOKING-' . $booking->id,
                    'gross_amount' => (int) $booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => [
                    [
                        'id' => $booking->court_id,
                        'price' => (int) $booking->court->price_per_hour,
                        'quantity' => (int) Carbon::parse($booking->start_time)->diffInHours(Carbon::parse($booking->end_time)),
                        'name' => "Booking Lapangan: " . $booking->court->name,
                    ]
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $payment->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal menghubungkan ke layanan pembayaran: ' . $e->getMessage());
            }
        }

        return view('customer.payments.create', compact('booking', 'payment'));
    }

    public function storePayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('proof_of_payment')->store('payments', 'public');

        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'proof_of_payment' => $path,
                'status' => 'pending',
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi.');
    }

    public function checkAvailability(Request $request)
    {
        $courtId = $request->query('court_id');
        $date = $request->query('date');

        if (!$courtId || !$date) {
            return response()->json([], 400);
        }

        $bookings = Booking::where('court_id', $courtId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $slots = [];
        for ($i = 6; $i <= 22; $i++) {
            $time = sprintf('%02d:00', $i);
            $isAvailable = true;

            foreach ($bookings as $booking) {
                // Check if this time slot is within a booking range
                if ($time >= substr($booking->start_time, 0, 5) && $time < substr($booking->end_time, 0, 5)) {
                    $isAvailable = false;
                    break;
                }
            }

            $slots[] = [
                'time' => $time,
                'available' => $isAvailable
            ];
        }

        return response()->json($slots);
    }
}
