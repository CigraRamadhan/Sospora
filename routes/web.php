<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotifikasiController as AdminNotifikasiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\KategoriController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\SuperAdmin\PengaturanController;
use App\Http\Controllers\SuperAdmin\SaranaController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\NotifikasiController;
use Illuminate\Support\Facades\Route;

// === PUBLIC ROUTES ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sarana', [HomeController::class, 'katalog'])->name('sarana.index');
Route::get('/sarana/{slug}', [HomeController::class, 'detail'])->name('sarana.show');
Route::get('/api/sarana/{sarana}/ketersediaan', [HomeController::class, 'ketersediaan'])->name('sarana.ketersediaan');

// === AUTH ROUTES ===
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === USER PORTAL ROUTES ===
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Booking
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking-saya', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/booking-saya/{kode_booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking-saya/{booking}/batal', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking-saya/{booking}/upload-bukti', [BookingController::class, 'uploadBukti'])->name('booking.upload-bukti');
    Route::get('/booking-saya/{booking}/bukti-transfer', [BookingController::class, 'buktiTransfer'])->name('booking.bukti');
    Route::get('/riwayat', [BookingController::class, 'riwayat'])->name('booking.riwayat');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    Route::get('/api/notifikasi/unread-count', [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
});

// === ADMIN PORTAL ROUTES ===
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/booking', [AdminBookingController::class, 'index'])->name('booking.index');
    Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])->name('booking.show');
    Route::get('/booking/{booking}/bukti-transfer', [AdminBookingController::class, 'buktiTransfer'])->name('booking.bukti');
    Route::post('/booking/{booking}/setujui', [AdminBookingController::class, 'setujui'])->name('booking.setujui');
    Route::post('/booking/{booking}/tolak', [AdminBookingController::class, 'tolak'])->name('booking.tolak');
    Route::post('/booking/{booking}/konfirmasi-pembayaran', [AdminBookingController::class, 'konfirmasiPembayaran'])->name('booking.konfirmasi');
    Route::post('/booking/{booking}/selesai', [AdminBookingController::class, 'selesai'])->name('booking.selesai');
    Route::get('/notifikasi', [AdminNotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/baca', [AdminNotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::get('/api/notifikasi/unread-count', [AdminNotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
});

// === SUPER ADMIN PORTAL ROUTES ===
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Sarana CRUD
    Route::get('/sarana', [SaranaController::class, 'index'])->name('sarana.index');
    Route::get('/sarana/create', [SaranaController::class, 'create'])->name('sarana.create');
    Route::post('/sarana', [SaranaController::class, 'store'])->name('sarana.store');
    Route::get('/sarana/{sarana}/edit', [SaranaController::class, 'edit'])->name('sarana.edit');
    Route::put('/sarana/{sarana}', [SaranaController::class, 'update'])->name('sarana.update');
    Route::post('/sarana/{sarana}/toggle-status', [SaranaController::class, 'toggleStatus'])->name('sarana.toggle-status');
    Route::delete('/sarana/{sarana}', [SaranaController::class, 'destroy'])->name('sarana.destroy');
    Route::delete('/sarana/{sarana}/foto/{foto}', [SaranaController::class, 'deleteFoto'])->name('sarana.foto.destroy');

    // Kategori CRUD
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // Admin CRUD
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{admin}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{admin}', [AdminController::class, 'update'])->name('admin.update');
    Route::post('/admin/{admin}/toggle-status', [AdminController::class, 'toggleStatus'])->name('admin.toggle-status');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'edit'])->name('pengaturan.edit');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
