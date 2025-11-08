<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RkatController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\IkuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TahunAnggaranController;
use App\Http\Controllers\DashboardController; // <<< INI BARU
use App\Http\Controllers\MonitoringController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// ROUTE DASHBOARD DIPERBARUI
Route::get('/dashboard', [DashboardController::class, 'index']) // <<< MEMANGGIL CONTROLLER
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Semua rute yang memerlukan otentikasi
Route::middleware(['auth', 'verified'])->group(function () {

    // === RUTE MONITORING RKAT ===
    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index'); // <<< RUTE BARU DENGAN AKSES BERLAPIS

    // === RUTE MASTER DATA (TAHUN ANGGARAN) ===
    // Menggunakan route resource untuk CRUD Tahun Anggaran
    Route::resource('tahun', TahunAnggaranController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('tahun'); // Nama route: tahun.index, tahun.store, dll.

    // === RUTE MEMBER / UNIT ===
    Route::get('/member/units', [UnitController::class, 'index'])->name('member.unit.index');

    // === RUTE RKAT ===
    Route::get('/rkat/input', [RkatController::class, 'create'])->name('rkat.create');
    Route::post('/rkat', [RkatController::class, 'store'])->name('rkat.store');

    // === RUTE IKU ===
    Route::get('/iku/input', [IkuController::class, 'create'])->name('iku.create');
    Route::post('/iku', [IkuController::class, 'store'])->name('iku.store');
});

// Rute Persetujuan
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/approval', [ApprovalController::class, 'index'])->name('approver.index');
    Route::post('/approval/approve/{rkatHeader}', [ApprovalController::class, 'approve'])->name('approver.approve');
});

// Rute Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';