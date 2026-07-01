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
        if (!Schema::hasTable('membership_tiers')) {
            Schema::create('membership_tiers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 15, 2);
                $table->integer('discount_percentage')->default(0);
                $table->integer('discount_weekday')->default(0);
                $table->integer('discount_weekend')->default(0);
                $table->integer('discount_weekday_limit')->nullable();
                $table->integer('discount_weekend_limit')->nullable();
                $table->integer('duration_days')->default(30);
                $table->integer('booking_window_days')->default(2);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_tiers');
    }
};
