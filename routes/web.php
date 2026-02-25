<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Customer\BookingController as CustomerBooking;
use App\Http\Controllers\Customer\MembershipController as CustomerMembership;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CourtController as AdminCourt;
use App\Http\Controllers\Admin\MembershipTierController as AdminMembershipTier;
use App\Http\Controllers\Admin\BookingController as AdminBooking;
use App\Http\Controllers\Admin\LandingPageController as AdminLandingPage;
use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerBooking::class, 'index'])->name('welcome');
Route::get('/api/availability', [CustomerBooking::class, 'checkAvailability'])->name('api.availability');
Route::post('/midtrans/callback', [MidtransController::class, 'handleNotification'])->name('midtrans.callback');

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/my-bookings', [CustomerBooking::class, 'dashboard'])->name('dashboard');
    
    Route::get('/courts/{court}', [CustomerBooking::class, 'show'])->name('customer.courts.show');
    Route::post('/bookings', [CustomerBooking::class, 'store'])->name('customer.bookings.store');
    
    Route::get('/payment/finish', [CustomerBooking::class, 'paymentFinish'])->name('payment.finish');
    
    Route::get('/bookings/{booking}/payment', [CustomerBooking::class, 'payment'])->name('customer.payments.create');
    Route::post('/bookings/{booking}/payment', [CustomerBooking::class, 'storePayment'])->name('customer.payments.store');

    Route::get('/membership', [CustomerMembership::class, 'index'])->name('membership.index');
    Route::post('/membership/subscribe/{tier}', [CustomerMembership::class, 'subscribe'])->name('membership.subscribe');
    Route::post('/membership/check-status', [CustomerMembership::class, 'checkStatus'])->name('membership.check-status');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('courts', AdminCourt::class);
    Route::resource('membership-tiers', AdminMembershipTier::class);
    
    Route::get('/bookings', [AdminBooking::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/approve', [AdminBooking::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/cancel', [AdminBooking::class, 'cancel'])->name('bookings.cancel');
    
    Route::post('/payments/{payment}/verify', [AdminBooking::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [AdminBooking::class, 'rejectPayment'])->name('payments.reject');
    
    // Landing Page Management
    Route::get('/landing', [AdminLandingPage::class, 'index'])->name('landing.edit');
    Route::put('/landing', [AdminLandingPage::class, 'update'])->name('landing.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/bookings/{booking}/receipt', [CustomerBooking::class, 'downloadReceipt'])->name('bookings.receipt');
    Route::get('/memberships/{membership}/receipt', [CustomerMembership::class, 'downloadReceipt'])->name('memberships.receipt');
});

require __DIR__.'/auth.php';
