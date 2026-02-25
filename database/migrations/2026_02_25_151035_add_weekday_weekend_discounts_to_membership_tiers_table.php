<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('membership_tiers', function (Blueprint $table) {
            if (!Schema::hasColumn('membership_tiers', 'discount_weekday')) {
                $table->integer('discount_weekday')->default(0)->after('discount_percentage');
            }
            if (!Schema::hasColumn('membership_tiers', 'discount_weekend')) {
                $table->integer('discount_weekend')->default(0)->after('discount_weekday');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_tiers', function (Blueprint $table) {
            $table->dropColumn(['discount_weekday', 'discount_weekend']);
        });
    }
};
