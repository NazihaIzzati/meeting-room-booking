<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Availability\AvailabilityController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\MeetingRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\AuditController;

// =====================
// PUBLIC ROUTES
// =====================

Route::get('/', [PublicController::class, 'index'])->name('landing');

// Booking CRUD (public)
Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/book', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
Route::post('/bookings/{booking}/edit', [BookingController::class, 'update'])->name('bookings.update');
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
Route::get('/bookings/series/{parent}', [BookingController::class, 'series'])->name('bookings.series');
Route::post('/bookings/series/{parent}/cancel', [BookingController::class, 'cancelSeries'])->name('bookings.series.cancel');

// Availability (public)
Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');

// Booking lookup by email (public)
Route::get('/booking-lookup', [BookingController::class, 'lookupForm'])->name('booking.lookup.form');
Route::post('/booking-lookup', [BookingController::class, 'lookup'])->name('booking.lookup');

// =====================
// AUTH (Admin Only)
// =====================

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

// =====================
// ADMIN ROUTES
// =====================

Route::prefix('admin')->middleware(['auth', 'admin'])->as('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Room Management (Full CRUD)
    Route::resource('rooms', RoomController::class);
    Route::resource('meeting-rooms', MeetingRoomController::class)->names('rooms');
    Route::patch('/meeting-rooms/{room}/status', [MeetingRoomController::class, 'updateStatus'])->name('rooms.status');

    // Booking Management (Full CRUD)
    Route::resource('bookings', AdminBookingController::class)->except(['create', 'store'])->names('bookings');
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    
    // Approval actions (now inside admin group)
    Route::get('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::post('/bookings/{booking}/approve', [AdminBookingController::class, 'approveBooking'])->name('bookings.approveBooking');
    Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');

    // Audit Logs (READ operations)
    Route::get('/audit-logs', [AuditController::class, 'index'])->name('audit-logs.index');
    Route::get('/audit-logs/{auditLog}', [AuditController::class, 'show'])->name('audit-logs.show');
    Route::get('/audit-logs/export', [AuditController::class, 'export'])->name('audit-logs.export');
});


