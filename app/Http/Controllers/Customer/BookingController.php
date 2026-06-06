<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Booking;
use App\Models\LandingPageContent;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    protected $bookingService;
    protected $paymentService;
    protected $membershipService;

    public function __construct(
        BookingService $bookingService,
        PaymentService $paymentService,
        MembershipService $membershipService
    ) {
        $this->bookingService = $bookingService;
        $this->paymentService = $paymentService;
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        $preview = request()->has('preview');
        if (Auth::check() && Auth::user()->role === \App\Models\User::ROLE_ADMIN && !$preview) {
            abort(404);
        }

        $courts = Court::where('is_active', true)->get();
        $faqs = \App\Models\Faq::orderBy('order')->get();
        
        $landingContent = null;
        $landingExtras = [];
        
        if ($preview && \Illuminate\Support\Facades\Storage::disk('public')->exists('landing/draft.json')) {
            $draftData = json_decode(\Illuminate\Support\Facades\Storage::disk('public')->get('landing/draft.json'), true);
            $landingContent = new LandingPageContent();
            $landingContent->forceFill($draftData['db_fields'] ?? []);
            $landingExtras = $draftData['extras_fields'] ?? [];
        } else {
            $landingContent = LandingPageContent::first() ?? new LandingPageContent();
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('landing/extras.json')) {
                $landingExtras = json_decode(\Illuminate\Support\Facades\Storage::disk('public')->get('landing/extras.json'), true);
            }
        }
        
        return view('welcome', compact('courts', 'landingContent', 'faqs', 'landingExtras'));
    }

    public function show(Court $court)
    {
        $landingContent = LandingPageContent::first() ?? new LandingPageContent();
        $allowedDays = Auth::check() ? Auth::user()->getAllowedBookingWindow() : 2;
        $maxDate = now()->addDays($allowedDays)->format('Y-m-d');
        
        $user = Auth::user();
        $membership = $user ? $user->activeMembership() : null;
        $discountWeekday = $membership ? $membership->tier->discount_weekday : 0;
        $discountWeekend = $membership ? $membership->tier->discount_weekend : 0;
        
        return view('customer.courts.show', compact('court', 'landingContent', 'allowedDays', 'maxDate', 'discountWeekday', 'discountWeekend'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1|max:5',
        ]);

        $court = Court::findOrFail($request->court_id);
        $bookingDate = \Carbon\Carbon::parse($request->booking_date);
        $user = Auth::user();
        
        // Check booking window
        $allowedDays = $user->getAllowedBookingWindow();
        $maxDate = now()->addDays($allowedDays)->endOfDay();
        
        if ($bookingDate->gt($maxDate)) {
            $msg = $user->activeMembership() 
                ? "Sebagai member {$user->activeMembership()->tier->name}, Anda hanya dapat memesan maksimal {$allowedDays} hari ke depan."
                : "Untuk non-member, pemesanan maksimal adalah {$allowedDays} hari ke depan. Silakan daftar membership untuk mendapatkan akses booking lebih awal.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors(['error' => $msg])->withInput();
        }

        $endTime = \Carbon\Carbon::parse($request->start_time)->addHours((int) $request->duration)->format('H:i');

        if (!$this->bookingService->isTimeRangeAvailable($court->id, $request->booking_date, $request->start_time, $endTime)) {
            return back()->withErrors(['error' => 'Jadwal yang Anda pilih sudah terisi. Silakan pilih waktu lain.'])->withInput();
        }

        $pricing = $this->bookingService->calculatePricing($court, $request->booking_date, $request->duration, Auth::user());
        
        $booking = $this->bookingService->createBooking(array_merge($request->all(), $pricing), Auth::id());

        $snapToken = $this->paymentService->getBookingSnapToken($booking, Auth::user());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'booking_id' => $booking->id,
                'message' => 'Booking berhasil dibuat. Silakan selesaikan pembayaran.'
            ]);
        }

        return redirect()->route('dashboard', ['pay' => $snapToken])->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran.');
    }

    public function dashboard()
    {
        $this->paymentService->expireStalePayments();
        $user = Auth::user();
        $bookings = Booking::with(['court', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $memberships = \App\Models\Membership::with(['tier', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Sync statuses and ensure tokens
        foreach ($bookings as $booking) {
            if ($booking->status === 'pending' || $booking->status === 'unpaid') {
                $orderId = ($booking->payment && $booking->payment->order_id) 
                    ? $booking->payment->order_id 
                    : ('BOOKING-' . $booking->id);
                $this->paymentService->syncTransactionStatus($orderId);
                $this->paymentService->getBookingSnapToken($booking, $user);
            }
        }

        foreach ($memberships as $membership) {
            if ($membership->status === 'pending') {
                $orderId = ($membership->payment && $membership->payment->order_id) 
                    ? $membership->payment->order_id 
                    : ('MEMBERSHIP-' . $membership->id);
                $this->paymentService->syncTransactionStatus($orderId);
                $this->paymentService->getMembershipSnapToken($membership, $user);
            }
        }

        // Re-fetch to get updated payment relations
        $bookings = Booking::with(['court', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $memberships = \App\Models\Membership::with(['tier', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $landingContent = LandingPageContent::first() ?? new LandingPageContent();
        return view('dashboard', compact('bookings', 'memberships', 'landingContent'));
    }

    public function payment(Booking $booking)
    {
        $this->authorizeAccess($booking);

        $snapToken = $this->paymentService->getBookingSnapToken($booking, Auth::user());
        $payment = $booking->payment;
        $landingContent = LandingPageContent::first() ?? new LandingPageContent();

        return view('customer.payments.create', compact('booking', 'payment', 'landingContent'));
    }

    public function storePayment(Request $request, Booking $booking)
    {
        $this->authorizeAccess($booking);

        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('proof_of_payment')->store('payments', 'public');

        $booking->payment()->updateOrCreate([], [
            'proof_of_payment' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Bukti pembayaran berhasil diupload. Admin akan segera memverifikasi.');
    }

    public function checkAvailability(Request $request)
    {
        $courtId = $request->query('court_id');
        $date = $request->query('date');

        if (!$courtId || !$date) {
            return response()->json([], 400);
        }

        $slots = $this->bookingService->getAvailableSlots($courtId, $date);

        return response()->json($slots);
    }

    public function downloadReceipt(Booking $booking)
    {
        $this->authorizeAccess($booking);

        $booking->load(['court', 'user', 'payment']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.bookings.receipt', compact('booking'));
        
        return $pdf->download('Receipt-Booking-' . $booking->id . '.pdf');
    }

    public function paymentFinish()
    {
        return redirect()->route('dashboard');
    }

    private function authorizeAccess($booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
