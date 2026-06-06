<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageContent;

class LandingPageSeeder extends Seeder
{
    public function run()
    {
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
                'contact_address' => 'Jl. Padel Utama No. 88, Kebayoran Baru, Jakarta Selatan',
                'contact_phone' => '+62 812 9999 8888',
                'contact_email' => 'hello@padelhub.com',
                'whatsapp_number' => '6281299998888',
            ]
        );
    }
}
