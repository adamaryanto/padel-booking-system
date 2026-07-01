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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_per_hour', 15, 2)->nullable();
            $table->decimal('price_weekday', 10, 2)->nullable();
            $table->decimal('price_weekend', 10, 2)->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->text('member_promo')->nullable();
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
