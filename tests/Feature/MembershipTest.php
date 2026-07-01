<?php

use App\Models\User;
use App\Models\MembershipTier;
use App\Models\Membership;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can subscribe to membership and creates a pending membership', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $tier = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 100000,
        'description' => 'Test',
        'discount_weekday' => 10,
        'discount_weekend' => 5,
        'booking_window_days' => 3,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->post(route('membership.subscribe', $tier));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true
    ]);

    expect(Membership::count())->toBe(1);
    $membership = Membership::first();
    expect($membership->status)->toBe('pending');
    expect($membership->membership_tier_id)->toBe($tier->id);
    expect($membership->user_id)->toBe($user->id);
});

test('subscribing again to the same tier cancels previous pending membership and creates a new one', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $tier = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 100000,
        'description' => 'Test',
        'discount_weekday' => 10,
        'discount_weekend' => 5,
        'booking_window_days' => 3,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    // Create first pending membership
    $membership1 = Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $tier->id,
        'status' => 'pending'
    ]);

    // Subscribe again to the same tier
    $response = $this->actingAs($user)->post(route('membership.subscribe', $tier));

    $response->assertStatus(200);
    
    // There should be 2 membership records now
    expect(Membership::count())->toBe(2);
    
    // The first one should be cancelled
    $membership1->refresh();
    expect($membership1->status)->toBe('cancelled');
    
    // The new one should be pending
    $newMembership = Membership::latest('id')->first();
    expect($newMembership->id)->not->toBe($membership1->id);
    expect($newMembership->status)->toBe('pending');
});

test('subscribing to a different tier cancels previous pending membership and creates a new one', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $silver = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 100000,
        'description' => 'Test',
        'discount_weekday' => 10,
        'discount_weekend' => 5,
        'booking_window_days' => 3,
        'duration_days' => 30,
        'is_active' => true,
    ]);
    
    $gold = MembershipTier::create([
        'name' => 'Gold Member',
        'price' => 200000,
        'description' => 'Test',
        'discount_weekday' => 20,
        'discount_weekend' => 10,
        'booking_window_days' => 5,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    // Create first pending membership for Silver
    $membership1 = Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $silver->id,
        'status' => 'pending'
    ]);

    // Subscribe to Gold
    $response = $this->actingAs($user)->post(route('membership.subscribe', $gold));

    $response->assertStatus(200);
    
    // There should be 2 membership records now
    expect(Membership::count())->toBe(2);
    
    // The first one should be cancelled
    $membership1->refresh();
    expect($membership1->status)->toBe('cancelled');
    
    // The new one should be pending for Gold
    $newMembership = Membership::latest('id')->first();
    expect($newMembership->membership_tier_id)->toBe($gold->id);
    expect($newMembership->status)->toBe('pending');
});

test('user cannot downgrade active membership', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $silver = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 100000,
        'description' => 'Test',
        'discount_weekday' => 10,
        'discount_weekend' => 5,
        'booking_window_days' => 3,
        'duration_days' => 30,
        'is_active' => true,
    ]);
    
    $gold = MembershipTier::create([
        'name' => 'Gold Member',
        'price' => 200000,
        'description' => 'Test',
        'discount_weekday' => 20,
        'discount_weekend' => 10,
        'booking_window_days' => 5,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    // Create active membership for Gold
    Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $gold->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30)
    ]);

    // Attempt to downgrade to Silver
    $response = $this->actingAs($user)->post(route('membership.subscribe', $silver));

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Anda tidak dapat melakukan downgrade paket membership aktif Anda.'
    ]);
});

test('user cannot re-subscribe to current active tier', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $silver = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 100000,
        'description' => 'Test',
        'discount_weekday' => 10,
        'discount_weekend' => 5,
        'booking_window_days' => 3,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    // Create active membership for Silver
    Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $silver->id,
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30)
    ]);

    // Attempt to subscribe to Silver again
    $response = $this->actingAs($user)->post(route('membership.subscribe', $silver));

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
        'message' => 'Anda sudah berlangganan paket ini.'
    ]);
});

test('user upgrades membership and pays only the price difference', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    
    $silver = MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 250000,
        'discount_percentage' => 10,
        'duration_days' => 30,
        'is_active' => true,
    ]);
    
    $gold = MembershipTier::create([
        'name' => 'Gold Member',
        'price' => 500000,
        'discount_percentage' => 18,
        'duration_days' => 30,
        'is_active' => true,
    ]);

    // Create an active Silver membership
    Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $silver->id,
        'status' => 'active',
        'start_date' => now()->subDays(5),
        'end_date' => now()->addDays(25),
    ]);

    // Subscribe to Gold
    $response = $this->actingAs($user)->post(route('membership.subscribe', $gold));
    $response->assertStatus(200);

    // Get the pending membership
    $pendingMembership = Membership::where('user_id', $user->id)
        ->where('status', 'pending')
        ->first();
    
    expect($pendingMembership)->not->toBeNull();
    
    // Verify the payment gross_amount is only the difference
    $payment = $pendingMembership->payment;
    expect($payment)->not->toBeNull();
    expect((int)$payment->gross_amount)->toBe(250000); // 500000 - 250000
});
