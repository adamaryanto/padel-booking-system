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
        Schema::table('courts', function (Blueprint $table) {
            $table->decimal('price_weekday', 10, 2)->after('price_per_hour')->nullable();
            $table->decimal('price_weekend', 10, 2)->after('price_weekday')->nullable();
            $table->time('open_time')->after('price_weekend')->nullable();
            $table->time('close_time')->after('open_time')->nullable();
            $table->text('member_promo')->after('close_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn(['price_weekday', 'price_weekend', 'open_time', 'close_time', 'member_promo']);
        });
    }
};
