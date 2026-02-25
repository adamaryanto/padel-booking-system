<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Membership;
use App\Models\Payment;

$expiryTime = now()->subMinutes(10);
echo "Expiry threshold: " . $expiryTime->toDateTimeString() . "\n";

$b = Booking::where('status', 'pending')->where('created_at', '<', $expiryTime)->get(['id', 'created_at']);
$m = Membership::where('status', 'pending')->where('created_at', '<', $expiryTime)->get(['id', 'created_at']);
$p = Payment::where('status', 'pending')->where('created_at', '<', $expiryTime)->get(['id', 'created_at']);

echo "Pending Bookings: " . $b->count() . "\n";
foreach($b as $item) echo " - Booking ID: {$item->id}, Created: {$item->created_at}\n";

echo "Pending Memberships: " . $m->count() . "\n";
foreach($m as $item) echo " - Membership ID: {$item->id}, Created: {$item->created_at}\n";

echo "Pending Payments: " . $p->count() . "\n";
foreach($p as $item) echo " - Payment ID: {$item->id}, Created: {$item->created_at}\n";
