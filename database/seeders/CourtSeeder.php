<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CourtSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Court::truncate();
        Schema::enableForeignKeyConstraints();

        Court::create([
            'name' => 'Padel Arena Jakarta',
            'price_per_hour' => 150000,
            'price_weekday' => 150000,
            'price_weekend' => 150000,
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'member_promo' => 'Diskon 10% untuk Silver Member',
            'description' => 'Lapangan padel premium berstandar internasional dengan rumput sintetis ultra-grip, berlokasi strategis di Jakarta Selatan.',
            'facilities' => 'Indoor Court, Ruang Ganti, Area Parkir, Kafe & Lounge, Shower Area',
            'photo' => 'courts/court_outdoor.png',
            'is_active' => true,
        ]);

        Court::create([
            'name' => 'Elite Padel Club',
            'price_per_hour' => 175000,
            'price_weekday' => 175000,
            'price_weekend' => 175000,
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'member_promo' => 'Diskon 18% untuk Gold Member',
            'description' => 'Arena padel eksklusif di Jakarta Barat dengan fasilitas lengkap, pencahayaan LED anti-glare, dan area lounge nyaman.',
            'facilities' => 'Outdoor Court, Ruang Ganti, Area Parkir, Kafe & Lounge, Penyewaan Raket, Penyewaan Bola',
            'photo' => 'courts/court_indoor.png',
            'is_active' => true,
        ]);

        Court::create([
            'name' => 'Urban Padel Center',
            'price_per_hour' => 200000,
            'price_weekday' => 200000,
            'price_weekend' => 200000,
            'open_time' => '06:00:00',
            'close_time' => '22:00:00',
            'member_promo' => 'Diskon 25% untuk Platinum Member',
            'description' => 'Pusat latihan padel modern di Tangerang dengan lapangan panoramik dan pelatih bersertifikat.',
            'facilities' => 'Panoramic Court, Ruang Ganti, Area Parkir, Kafe & Lounge, Shower Area, Penyewaan Raket',
            'photo' => 'courts/court_panoramic.png',
            'is_active' => true,
        ]);
    }
}
