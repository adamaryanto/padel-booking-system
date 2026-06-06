<?php

namespace Database\Seeders;

use App\Models\MembershipTier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MembershipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        MembershipTier::truncate();
        Schema::enableForeignKeyConstraints();

        MembershipTier::create([
            'name' => 'Silver Member',
            'price' => 250000,
            'discount_percentage' => 10,
            'duration_days' => 30,
            'description' => "- Diskon Booking 10%\n- Booking H-3\n- Free Air Mineral & Handuk",
            'is_active' => true,
        ]);

        MembershipTier::create([
            'name' => 'Gold Member',
            'price' => 500000,
            'discount_percentage' => 18,
            'duration_days' => 30,
            'description' => "- Diskon Booking 18%\n- Prioritas Slot Prime Time\n- Booking H-5\n- Free Air Mineral & Handuk",
            'is_active' => true,
        ]);

        MembershipTier::create([
            'name' => 'Platinum Member',
            'price' => 1000000,
            'discount_percentage' => 25,
            'duration_days' => 30,
            'description' => "- Diskon Booking 25%\n- Prioritas Slot Prime Time\n- Booking H-7\n- Free Minuman Isotonik & Handuk",
            'is_active' => true,
        ]);
    }
}
