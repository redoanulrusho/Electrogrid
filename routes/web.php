<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ConsumerDashboardController;
use App\Http\Controllers\FeederController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// ─── Root Redirect ─────────────────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Authentication Routes ─────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::get('/register',  [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// ─── Admin Routes (protected by admin.auth middleware) ─────────────────────
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Feeder actions
    Route::post('/feeders/{id}/status',          [FeederController::class, 'updateStatus'])->name('feeders.status');
    Route::get('/feeders/{id}/telemetry',        [FeederController::class, 'telemetry'])->name('feeders.telemetry');
    Route::post('/feeders/{id}/schedule-outage', [FeederController::class, 'scheduleOutage'])->name('feeders.schedule-outage');
    Route::get('/feeders/report',                [FeederController::class, 'report'])->name('feeders.report');

    // Tickets management
    Route::get('/tickets',           [TicketController::class, 'adminIndex'])->name('tickets');
    Route::post('/tickets/{id}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');

    // Mapbox complaints GeoJSON (used by heatmap layer)
    Route::get('/map/complaints-geojson', [AdminDashboardController::class, 'complaintsGeoJson'])->name('map.complaints');
});

// ─── Consumer Routes (protected by auth:web + consumer.auth) ───────────────
Route::middleware(['auth:web', 'consumer.auth'])->prefix('consumer')->name('consumer.')->group(function () {

    // Dashboard & status endpoint
    Route::get('/dashboard', [ConsumerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/feeder-status', [ConsumerDashboardController::class, 'statusJson'])->name('feeder-status');

    // Bills
    Route::get('/bills', [BillController::class, 'index'])->name('bills');

    // Tickets
    Route::get('/tickets',        [TicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets',       [TicketController::class, 'store'])->name('tickets.store');
});

