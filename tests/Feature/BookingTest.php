<?php

use App\Models\Court;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it prevents double booking on same court and time', function () {
    $user1 = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $user2 = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // First booking: 10:00 - 12:00
    Booking::create([
        'user_id' => $user1->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'pending'
    ]);

    // Second booking attempt: 11:00 - 12:00 (Overlap)
    $response = $this->actingAs($user2)->post(route('customer.bookings.store'), [
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '11:00',
        'duration' => 1
    ]);

    $response->assertSessionHasErrors(['error']);
    expect(Booking::count())->toBe(1);
});

test('it allows booking on different time', function () {
    $user1 = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // First booking: 10:00 - 12:00
    Booking::create([
        'user_id' => $user1->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'pending'
    ]);

    // Second booking attempt: 14:00 - 15:00 (No overlap)
    $response = $this->actingAs($user1)->post(route('customer.bookings.store'), [
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '14:00',
        'duration' => 1
    ]);

    $response->assertRedirect();
    $this->assertStringStartsWith(route('dashboard'), $response->headers->get('Location'));
    expect(Booking::count())->toBe(2);
});

test('it automatically completes past approved bookings but leaves future ones', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Past booking (approved)
    $pastApproved = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->subDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'total_price' => 100000,
        'status' => 'approved'
    ]);

    // Future booking (approved)
    $futureApproved = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'total_price' => 100000,
        'status' => 'approved'
    ]);

    // Past booking (pending)
    $pastPending = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->subDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '11:00',
        'total_price' => 100000,
        'status' => 'pending'
    ]);

    // Run the command
    $this->artisan('app:complete-past-bookings')
        ->assertExitCode(0);

    // Assertions
    expect($pastApproved->fresh()->status)->toBe('completed');
    expect($futureApproved->fresh()->status)->toBe('approved');
    expect($pastPending->fresh()->status)->toBe('pending');
});

test('non-member cannot reschedule to a different day', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: tomorrow 10:00 - 12:00
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Reschedule attempt to day after tomorrow
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDays(2)->format('Y-m-d'),
        'start_time' => '10:00'
    ]);

    $response->assertSessionHas('error', 'Sebagai non-member, Anda hanya dapat melakukan reschedule jadwal pada hari yang sama dengan pesanan awal.');
    
    // Assert original booking remains unchanged
    expect($booking->fresh()->booking_date)->toBe(now()->addDay()->format('Y-m-d'));
});

test('non-member can reschedule to a different slot on the same day', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: tomorrow 10:00 - 12:00 (2 hours)
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Reschedule attempt to tomorrow 14:00 (same day)
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '14:00'
    ]);

    $response->assertSessionHas('success', 'Jadwal booking berhasil di-reschedule.');
    
    // Assert original booking is updated
    $freshBooking = $booking->fresh();
    expect($freshBooking->booking_date)->toBe(now()->addDay()->format('Y-m-d'));
    expect(substr($freshBooking->start_time, 0, 5))->toBe('14:00');
    expect(substr($freshBooking->end_time, 0, 5))->toBe('16:00'); // Duration of 2 hours preserved
});

test('rescheduling fails if target slot conflicts with another booking', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $otherUser = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // User's booking: tomorrow 10:00 - 12:00
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Conflicting booking: tomorrow 14:00 - 15:00
    Booking::create([
        'user_id' => $otherUser->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '14:00',
        'end_time' => '15:00',
        'total_price' => 100000,
        'status' => 'approved'
    ]);

    // Attempt reschedule to tomorrow 13:00 - 15:00 (overlaps with conflicting booking)
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '13:00'
    ]);

    $response->assertSessionHas('error', 'Jadwal baru yang Anda pilih sudah terisi atau tidak tersedia. Silakan pilih waktu lain.');
    
    // Assert original booking remains unchanged
    expect(substr($booking->fresh()->start_time, 0, 5))->toBe('10:00');
});

test('can reschedule to a slot overlapping with own original slot', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: tomorrow 10:00 - 12:00
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Attempt reschedule to tomorrow 11:00 (overlaps with 10:00-12:00)
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '11:00'
    ]);

    $response->assertSessionHas('success', 'Jadwal booking berhasil di-reschedule.');
    
    $freshBooking = $booking->fresh();
    expect(substr($freshBooking->start_time, 0, 5))->toBe('11:00');
    expect(substr($freshBooking->end_time, 0, 5))->toBe('13:00');
});

test('member can reschedule to future days within their allowed booking window', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    
    // Create membership tier with 5 days booking window
    $tier = App\Models\MembershipTier::create([
        'name' => 'Gold Member',
        'price' => 500000,
        'discount_percentage' => 18,
        'duration_days' => 30,
        'booking_window_days' => 5,
        'is_active' => true,
    ]);

    // Create active membership
    App\Models\Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $tier->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date' => now()->addDays(25)->format('Y-m-d'),
        'status' => 'active'
    ]);

    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: tomorrow 10:00 - 12:00
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Attempt reschedule to 4 days from now (within 5-day window)
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDays(4)->format('Y-m-d'),
        'start_time' => '10:00'
    ]);

    $response->assertSessionHas('success', 'Jadwal booking berhasil di-reschedule.');
    
    $freshBooking = $booking->fresh();
    expect($freshBooking->booking_date)->toBe(now()->addDays(4)->format('Y-m-d'));
});

test('member cannot reschedule to a day outside their allowed booking window', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    
    // Create membership tier with 3 days booking window
    $tier = App\Models\MembershipTier::create([
        'name' => 'Silver Member',
        'price' => 250000,
        'discount_percentage' => 10,
        'duration_days' => 30,
        'booking_window_days' => 3,
        'is_active' => true,
    ]);

    // Create active membership
    App\Models\Membership::create([
        'user_id' => $user->id,
        'membership_tier_id' => $tier->id,
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date' => now()->addDays(25)->format('Y-m-d'),
        'status' => 'active'
    ]);

    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: tomorrow 10:00 - 12:00
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'approved'
    ]);

    // Attempt reschedule to 5 days from now (outside 3-day window)
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'start_time' => '10:00'
    ]);

    $response->assertSessionHas('error', 'Sebagai member, Anda hanya dapat memilih jadwal baru maksimal 3 hari ke depan.');
    
    // Assert original booking remains unchanged
    expect($booking->fresh()->booking_date)->toBe(now()->addDay()->format('Y-m-d'));
});

test('cannot reschedule a booking that is not approved', function () {
    $user = User::factory()->create(['role' => User::ROLE_CUSTOMER]);
    $court = Court::create([
        'name' => 'Court A',
        'price_per_hour' => 100000,
        'is_active' => true
    ]);

    // Original booking: pending
    $booking = Booking::create([
        'user_id' => $user->id,
        'court_id' => $court->id,
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '10:00',
        'end_time' => '12:00',
        'total_price' => 200000,
        'status' => 'pending'
    ]);

    // Attempt reschedule
    $response = $this->actingAs($user)->post(route('customer.bookings.reschedule', $booking), [
        'booking_date' => now()->addDay()->format('Y-m-d'),
        'start_time' => '14:00'
    ]);

    $response->assertSessionHas('error', 'Pemesanan harus berstatus disetujui (approved) untuk di-reschedule.');
    
    // Assert original booking remains unchanged
    expect(substr($booking->fresh()->start_time, 0, 5))->toBe('10:00');
});

