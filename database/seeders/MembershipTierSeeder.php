<?php

namespace Database\Seeders;

use App\Models\MembershipTier;
use Illuminate\Database\Seeder;

class MembershipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MembershipTier::create([
            'name' => 'Basic Member',
            'price' => 350000,
            'discount_percentage' => 15,
            'duration_days' => 30,
            'description' => "- Diskon 15%\n- Booking H-3\n- Free Air Mineral",
            'is_active' => true,
        ]);

        MembershipTier::create([
            'name' => 'Pro Member',
            'price' => 999000,
            'discount_percentage' => 20,
            'duration_days' => 30,
            'description' => "- Diskon 20%\n- Free 2 Jam Main\n- Prioritas Slot Prime Time",
            'is_active' => true,
        ]);

        MembershipTier::create([
            'name' => 'Paket Jam',
            'price' => 2500000,
            'discount_percentage' => 0,
            'duration_days' => 30,
            'description' => "- Playtime 8-12 Jam\n- Sisa jam hangus",
            'is_active' => true,
        ]);
    }
}
