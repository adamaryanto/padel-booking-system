<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_prevents_double_booking_on_same_court_and_time()
    {
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
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'total_price' => 200000,
            'status' => 'pending'
        ]);

        // Second booking attempt: 11:00 - 12:00 (Overlap)
        $response = $this->actingAs($user2)->post(route('customer.bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '11:00',
            'duration' => 1
        ]);

        $response->assertSessionHasErrors(['error']);
        $this->assertEquals(1, Booking::count());
    }

    /** @test */
    public function it_allows_booking_on_different_time()
    {
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
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'total_price' => 200000,
            'status' => 'pending'
        ]);

        // Second booking attempt: 14:00 - 15:00 (No overlap)
        $response = $this->actingAs($user1)->post(route('customer.bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '14:00',
            'duration' => 1
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertEquals(2, Booking::count());
    }
}
