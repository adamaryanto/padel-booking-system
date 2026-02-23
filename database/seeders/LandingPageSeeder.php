<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LandingPageContent;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LandingPageContent::create([
            'hero_title' => 'LOCKED PEEK PERFORMANCE',
            'hero_subtitle' => 'ULTIMATE PADEL EXPERIENCE',
            'hero_image' => 'https://images.unsplash.com/photo-1626248801379-51a0748a5f96?auto=format&fit=crop&q=80&w=2070',
            'hero_cta_text' => 'BOOK ARENA',
            'hero_cta_link' => '#courts',
            'about_title' => 'The Future of Padel is Here',
            'about_subtitle' => 'THE ARENA',
            'about_description' => 'Kami menghadirkan standar baru dalam dunia Padel di Indonesia. Dengan fasilitas kelas satu, lapangan bersertifikasi internasional, dan ekosistem komunitas yang dinamis, PadelHub adalah rumah bagi para juara.',
            'about_image' => 'https://images.unsplash.com/photo-1593027552553-61b6c7030880?auto=format&fit=crop&q=80&w=2071',
            'contact_address' => 'Jl. Padel Champion No. 1, Jakarta Selatan',
            'contact_phone' => '+62 812 3456 7890',
            'contact_email' => 'hello@padelhub.id',
            'whatsapp_number' => '6281234567890',
        ]);
    }
}
