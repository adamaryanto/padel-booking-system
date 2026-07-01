<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageContent;

class LandingPageSeeder extends Seeder
{
    public function run()
    {
        // Ensure the landing storage directory exists
        $storageLandingPath = storage_path('app/public/landing');
        if (!file_exists($storageLandingPath)) {
            mkdir($storageLandingPath, 0755, true);
        }

        // Copy dummy images if they exist
        if (file_exists(public_path('images/hero.png')) && !file_exists($storageLandingPath . '/hero.png')) {
            copy(public_path('images/hero.png'), $storageLandingPath . '/hero.png');
        }

        if (file_exists(public_path('images/about.png')) && !file_exists($storageLandingPath . '/about.png')) {
            copy(public_path('images/about.png'), $storageLandingPath . '/about.png');
        }

        LandingPageContent::updateOrCreate(
            ['id' => 1],
            [
                'hero_title' => 'LEVEL UP YOUR GAME AT PADELHUB',
                'hero_subtitle' => 'THE ULTIMATE PADEL EXPERIENCE',
                'hero_cta_text' => 'PESAN LAPANGAN',
                'hero_cta_link' => '#courts',
                'about_title' => 'MASA DEPAN PADEL TELAH TIBA',
                'about_subtitle' => 'ARENA KAMI',
                'about_description' => 'PadelHub menghadirkan fasilitas berstandar World Padel Tour di tengah kota. Dengan permukaan lapangan ultra-grip, pencahayaan LED anti-glare, dan komunitas yang suportif, kami membantu Anda mencapai performa puncak di setiap pertandingan.',
                'about_image' => null,
                'contact_address' => 'Jl. Padel Utama No. 88, Kebayoran Baru, Jakarta Selatan',
                'contact_phone' => '+62 812 9999 8888',
                'contact_email' => 'hello@padelhub.com',
                'whatsapp_number' => '6281299998888',
            ]
        );
    }
}

