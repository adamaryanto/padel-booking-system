<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Carbon\Carbon;
$service = new \App\Services\BookingService();
$date = '2026-05-09';

echo "Current Server Time: " . now()->toDateTimeString() . "\n";
echo "Checking for date: $date\n";
echo "Is Today: " . (Carbon::parse($date)->isToday() ? 'Yes' : 'No') . "\n";

$slots = $service->getAvailableSlots(1, $date);

foreach ($slots as $slot) {
    echo $slot['time'] . ": " . ($slot['available'] ? 'Available' : 'Filled') . "\n";
}
