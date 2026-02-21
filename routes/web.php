<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\CustomerBookingController::class, 'index'])->name('welcome');
Route::get('/api/availability', [\App\Http\Controllers\CustomerBookingController::class, 'checkAvailability'])->name('api.availability');
Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransController::class, 'handleNotification'])->name('midtrans.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CustomerBookingController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/courts/{court}', [\App\Http\Controllers\CustomerBookingController::class, 'show'])->name('customer.courts.show');
    Route::post('/bookings', [\App\Http\Controllers\CustomerBookingController::class, 'store'])->name('customer.bookings.store');
    
    Route::get('/bookings/{booking}/payment', [\App\Http\Controllers\CustomerBookingController::class, 'payment'])->name('customer.payments.create');
    Route::post('/bookings/{booking}/payment', [\App\Http\Controllers\CustomerBookingController::class, 'storePayment'])->name('customer.payments.store');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('courts', \App\Http\Controllers\CourtController::class);
    
    Route::get('/bookings', [\App\Http\Controllers\AdminBookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{booking}/approve', [\App\Http\Controllers\AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/cancel', [\App\Http\Controllers\AdminBookingController::class, 'cancel'])->name('bookings.cancel');
    
    Route::post('/payments/{payment}/verify', [\App\Http\Controllers\AdminBookingController::class, 'verifyPayment'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [\App\Http\Controllers\AdminBookingController::class, 'rejectPayment'])->name('payments.reject');
    // Landing Page Management
    Route::get('/landing', [App\Http\Controllers\AdminLandingPageController::class, 'edit'])->name('landing.edit');
    Route::put('/landing', [App\Http\Controllers\AdminLandingPageController::class, 'update'])->name('landing.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
