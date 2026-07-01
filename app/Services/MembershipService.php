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
        // Cancel all existing pending memberships for this user
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
