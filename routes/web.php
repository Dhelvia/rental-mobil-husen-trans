<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminProfilController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\LaporanController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin panel (klik logo/teks kiri) -> edit profil
    Route::get('/admin/profil', [AdminProfilController::class, 'edit'])->name('admin.profil');
    Route::post('/admin/profil', [AdminProfilController::class, 'update'])->name('admin.profil.update');

    // Mobil
    Route::get('/mobil', [MobilController::class, 'index'])->name('mobil.index');
    Route::get('/mobil/tambah', [MobilController::class, 'create'])->name('mobil.create');
    Route::post('/mobil/tambah', [MobilController::class, 'store'])->name('mobil.store');
    Route::get('/mobil/{mobil}/edit', [MobilController::class, 'edit'])->name('mobil.edit');
    Route::post('/mobil/{mobil}/edit', [MobilController::class, 'update'])->name('mobil.update');
    Route::post('/mobil/{mobil}/hapus', [MobilController::class, 'destroy'])->name('mobil.destroy');

    // Booking dari mobil
    Route::get('/mobil/{mobil}/booking', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/mobil/{mobil}/booking', [BookingController::class, 'store'])->name('booking.store');

    // Transaksi
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{transaksi}/status', [TransaksiController::class, 'ubahStatus'])->name('transaksi.status');
    Route::post('/transaksi/{transaksi}/hapus', [TransaksiController::class, 'hapus'])->name('transaksi.hapus');
    Route::post('/transaksi/{transaksi}/update-modal', [TransaksiController::class, 'updateModal'])->name('transaksi.updateModal');

    // Data Penyewa (setelah selesai)
    Route::get('/data-penyewa', [PenyewaController::class, 'index'])->name('penyewa.index');
    Route::post('/data-penyewa/{penyewa}/update', [PenyewaController::class, 'update'])->name('penyewa.update');
    Route::post('/data-penyewa/{penyewa}/hapus', [PenyewaController::class, 'hapus'])->name('penyewa.hapus');

    /// Laporan (pemasukan & pengeluaran)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/{laporan}/pengeluaran', [LaporanController::class, 'tambahPengeluaran'])->name('laporan.pengeluaran');

    // edit/hapus pemasukan (laporan)
    Route::post('/laporan/{laporan}/update', [LaporanController::class, 'update'])->name('laporan.update');
    Route::post('/laporan/{laporan}/hapus', [LaporanController::class, 'hapus'])->name('laporan.hapus');

    // TAMBAH: edit/hapus pengeluaran
    Route::post('/pengeluaran/{pengeluaran}/update', [LaporanController::class, 'updatePengeluaran'])->name('pengeluaran.update');
    Route::post('/pengeluaran/{pengeluaran}/hapus', [LaporanController::class, 'hapusPengeluaran'])->name('pengeluaran.hapus');
});
