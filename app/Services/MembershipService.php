<?php

namespace App\Services;

use App\Models\Membership;
use App\Models\MembershipTier;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MembershipService
{
    /**
     * Subscribe a user to a membership tier.
     */
    public function subscribe(User $user, MembershipTier $tier): Membership
    {
        // Check if there's already a pending membership for the SAME tier
        $existing = Membership::where('user_id', $user->id)
            ->where('membership_tier_id', $tier->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return $existing;
        }

        // Cancel existing pending memberships for OTHER tiers
        Membership::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return Membership::create([
            'user_id' => $user->id,
            'membership_tier_id' => $tier->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Check if user has an active membership.
     */
    public function getActiveMembership(User $user): ?Membership
    {
        return $user->activeMembership();
    }
}
