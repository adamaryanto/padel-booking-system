<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Membership;
use App\Models\Payment;
use Carbon\Carbon;

$expiryTime = now()->subMinutes(10);
echo "Checking records older than: " . $expiryTime->toDateTimeString() . "\n";

$pendingBookings = Booking::where('status', 'pending')->where('created_at', '<', $expiryTime)->count();
$pendingMemberships = Membership::where('status', 'pending')->where('created_at', '<', $expiryTime)->count();
$pendingPayments = Payment::where('status', 'pending')->where('created_at', '<', $expiryTime)->count();

echo "Pending Bookings (>10m): $pendingBookings\n";
echo "Pending Memberships (>10m): $pendingMemberships\n";
echo "Pending Payments (>10m): $pendingPayments\n";
