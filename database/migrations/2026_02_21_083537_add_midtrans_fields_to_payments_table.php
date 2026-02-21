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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_of_payment')->nullable()->change();
            $table->string('snap_token')->nullable()->after('booking_id');
            $table->string('transaction_id')->nullable()->after('snap_token');
            $table->string('payment_type')->nullable()->after('transaction_id');
            $table->decimal('gross_amount', 15, 2)->nullable()->after('payment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_of_payment')->nullable(false)->change();
            $table->dropColumn(['snap_token', 'transaction_id', 'payment_type', 'gross_amount']);
        });
    }
};
