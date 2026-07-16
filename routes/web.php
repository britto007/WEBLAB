<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');
Route::get('/flights/{flight}', [FlightController::class, 'show'])->name('flights.show');

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('airlines', Admin\AirlineController::class)->except('show');
    Route::post('/airports/{airport}/weather', [Admin\AirportController::class, 'weather'])->name('airports.weather');
    Route::resource('airports', Admin\AirportController::class)->except('show');
    Route::resource('flights', Admin\FlightController::class)->except('show');
    Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}/cancel', [Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';
