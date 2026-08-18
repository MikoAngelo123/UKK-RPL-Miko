<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogAktivitasController;

// 1. Guest Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// 2. Authenticated Routes (All Roles)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Katalog Alat (Dapat dilihat oleh semua role)
    Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
    Route::get('/alat/{id}', [AlatController::class, 'show'])->name('alat.show');

    // Transaksi Peminjaman (Semua Role: Peminjam bisa ajukan pinjam & lihat riwayatnya)
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

    // 3. Khusus Petugas & Admin (Approval Peminjaman & Pengembalian)
    Route::middleware('role:admin,petugas')->group(function () {
        Route::patch('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::patch('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::patch('/peminjaman/{id}/return', [PeminjamanController::class, 'returnItem'])->name('peminjaman.return');

        // Manajemen Alat (Tambah, Edit, Hapus)
        Route::get('/alat-manage/create', [AlatController::class, 'create'])->name('alat.create');
        Route::post('/alat-manage', [AlatController::class, 'store'])->name('alat.store');
        Route::get('/alat-manage/{id}/edit', [AlatController::class, 'edit'])->name('alat.edit');
        Route::put('/alat-manage/{id}', [AlatController::class, 'update'])->name('alat.update');
        Route::delete('/alat-manage/{id}', [AlatController::class, 'destroy'])->name('alat.destroy');

        // Kategori Master
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });

    // 4. Khusus Admin (Manajemen User & Log Audit Sistem)
    Route::middleware('role:admin')->group(function () {
        Route::resource('user', UserController::class)->except(['show']);
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log.index');
    });
});
