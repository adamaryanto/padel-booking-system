<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpirePendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-pending-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings, memberships, and payments after 10 minutes of inactivity';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\PaymentService $paymentService)
    {
        $this->info("Checking for stale payments...");
        $paymentService->expireStalePayments();
        $this->info("Stale payments processed.");
    }
}
