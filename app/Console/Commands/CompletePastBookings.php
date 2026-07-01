<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BookingService;

class CompletePastBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:complete-past-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically complete past approved bookings whose end time has passed';

    /**
     * Execute the console command.
     */
    public function handle(BookingService $bookingService)
    {
        $this->info("Checking for past bookings to auto-complete...");
        $count = $bookingService->completePastBookings();
        $this->info("Auto-completed {$count} bookings.");
    }
}
