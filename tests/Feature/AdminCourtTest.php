<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCourtTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN ?? 'admin',
        ]);
    }

    /** @test */
    public function it_can_create_a_court_with_new_fields_and_multiple_images()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post(route('admin.courts.store'), [
            'name' => 'Test Court',
            'price_per_hour' => 100000,
            'price_weekday' => 80000,
            'price_weekend' => 120000,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'member_promo' => '10% Off',
            'description' => 'A nice court',
            'facilities' => 'Lighting, Water',
            'photo' => UploadedFile::fake()->image('court.jpg'),
            'additional_photos' => [
                UploadedFile::fake()->image('extra1.jpg'),
                UploadedFile::fake()->image('extra2.jpg'),
            ],
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.courts.index'));
        $this->assertDatabaseHas('courts', [
            'name' => 'Test Court',
            'price_weekday' => 80000,
            'price_weekend' => 120000,
        ]);

        $court = Court::where('name', 'Test Court')->first();
        $this->assertCount(2, $court->images);
        
        Storage::disk('public')->assertExists($court->photo);
        foreach ($court->images as $image) {
            Storage::disk('public')->assertExists($image->path);
        }
    }
}
