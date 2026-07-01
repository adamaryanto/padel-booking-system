<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MembershipTier;
use App\Models\Membership;
use App\Models\LandingPageContent;
use App\Services\MembershipService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    protected $membershipService;
    protected $paymentService;

    public function __construct(MembershipService $membershipService, PaymentService $paymentService)
    {
        $this->membershipService = $membershipService;
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $this->paymentService->expireStalePayments();
        $user = Auth::user();
        $tiers = MembershipTier::where('is_active', true)->get();
        $landingContent = LandingPageContent::first() ?? new LandingPageContent();
        $activeMembership = $this->membershipService->getActiveMembership($user);
        
        $hasPendingMembership = Membership::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        $pendingMembership = Membership::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('payment')
            ->latest()
            ->first();

        return view('customer.memberships.index', compact('tiers', 'landingContent', 'activeMembership', 'hasPendingMembership', 'pendingMembership'));
    }

    public function subscribe(MembershipTier $tier)
    {
        $user = Auth::user();
        $activeMembership = $user->activeMembership();
        
        if ($activeMembership && $activeMembership->tier) {
            if ($tier->price <= $activeMembership->tier->price && $tier->id !== $activeMembership->membership_tier_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat melakukan downgrade paket membership aktif Anda.'
                ], 422);
            }
            if ($tier->id === $activeMembership->membership_tier_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah berlangganan paket ini.'
                ], 422);
            }
        }
        
        $membership = $this->membershipService->subscribe($user, $tier);
        $snapToken = $this->paymentService->getMembershipSnapToken($membership, $user);

        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'message' => 'Pendaftaran membership berhasil. Silakan selesaikan pembayaran.'
        ]);
    }

    public function checkStatus(Request $request)
    {
        $user = Auth::user();
        $pendingMembership = Membership::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('payment')
            ->latest()
            ->first();

        if ($pendingMembership) {
            if ($pendingMembership->payment && $pendingMembership->payment->order_id) {
                $this->paymentService->syncTransactionStatus($pendingMembership->payment->order_id);
            }
            
            $pendingMembership->refresh();
            
            if ($pendingMembership->status === 'active') {
                return response()->json([
                    'success' => true,
                    'status' => 'active',
                    'tier_name' => $pendingMembership->tier->name,
                    'end_date' => $pendingMembership->end_date->format('d M Y'),
                    'discount' => ($pendingMembership->tier->discount_weekday ?? 0) + ($pendingMembership->tier->discount_weekend ?? 0),
                    'current_tier_id' => $pendingMembership->membership_tier_id,
                    'receipt_url' => route('memberships.receipt', $pendingMembership)
                ]);
            }
            
            return response()->json([
                'success' => true,
                'status' => 'pending'
            ]);
        }

        $activeMembership = $this->membershipService->getActiveMembership($user);

        if ($activeMembership) {
            return response()->json([
                'success' => true,
                'status' => 'idle',
                'tier_name' => $activeMembership->tier->name,
                'end_date' => $activeMembership->end_date->format('d M Y'),
                'discount' => ($activeMembership->tier->discount_weekday ?? 0) + ($activeMembership->tier->discount_weekend ?? 0),
                'current_tier_id' => $activeMembership->membership_tier_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'idle'
        ]);
    }

    public function downloadReceipt(Membership $membership)
    {
        if ($membership->user_id !== Auth::id()) {
            abort(403);
        }

        $membership->load(['tier', 'user', 'payment']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.memberships.receipt', compact('membership'));
        
        return $pdf->download('Receipt-Membership-' . $membership->id . '.pdf');
    }
}
