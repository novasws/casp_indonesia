<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/setup-admin', function () {
    $admin = \App\Models\Konsultan::updateOrCreate(
        ['username' => 'admin'],
        [
            'nama' => 'Superadmin',
            'gelar' => '',
            'spesialisasi' => 'System Administrator',
            'pengalaman_tahun' => 0,
            'inisial' => 'SA',
            'warna_avatar' => 'slate',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'is_superadmin' => true,
            'status' => 'online',
        ]
    );
    return "Berhasil! Username: admin | Password: password123";
});

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/layanan/{slug}', [LandingController::class, 'layananDetail'])->name('layanan.detail');
Route::get('/lacak-sesi', [LandingController::class, 'lacakSesi'])->name('lacak.sesi');
Route::post('/lacak-sesi', [LandingController::class, 'prosesLacakSesi'])->name('lacak.sesi.post');

/*
|--------------------------------------------------------------------------
| CS Live Chat (Keluhan)
|--------------------------------------------------------------------------
*/
Route::post('/keluhan/start', [KeluhanController::class, 'start'])->name('keluhan.start');
Route::get('/keluhan/{token}/fetch', [KeluhanController::class, 'fetch'])->name('keluhan.fetch');
Route::post('/keluhan/{token}/send', [KeluhanController::class, 'send'])->name('keluhan.send');

/*
|--------------------------------------------------------------------------
| Onboarding (multi-step)
|--------------------------------------------------------------------------
*/
Route::prefix('konsultasi')->name('onboarding.')->group(function () {
    Route::get('/',         [OnboardingController::class, 'index'])->name('index');
    Route::post('/step1',   [OnboardingController::class, 'step1'])->name('step1');
    Route::post('/step2',   [OnboardingController::class, 'step2'])->name('step2');
    Route::post('/step3',   [OnboardingController::class, 'step3'])->name('step3');
    Route::post('/pembayaran/init', [OnboardingController::class, 'initPembayaran'])->name('pembayaran.init');
});

/*
|--------------------------------------------------------------------------
| Pembayaran
|--------------------------------------------------------------------------
*/
Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
    Route::post('/konfirmasi', [PembayaranController::class, 'konfirmasi'])->name('konfirmasi');
    Route::get('/{id}/invoice', [PembayaranController::class, 'invoice'])->name('invoice');
    Route::post('/{id}/sukses', [PembayaranController::class, 'sukses'])->name('sukses');
    Route::post('/{id}/kadaluarsa', [PembayaranController::class, 'kadaluarsa'])->name('kadaluarsa');
    Route::post('/webhook',    [PembayaranController::class, 'webhook'])->name('webhook');
});

/*
|--------------------------------------------------------------------------
| Chat Konsultasi
|--------------------------------------------------------------------------
*/
Route::prefix('chat')->name('chat.')->middleware(['web'])->group(function () {
    Route::get('/{konsultasiId}',          [ChatController::class, 'index'])->name('index');
    Route::post('/{konsultasiId}/pesan',   [ChatController::class, 'kirimPesan'])->name('kirim-pesan');
    Route::get('/{konsultasiId}/fetch-pesan', [ChatController::class, 'fetchPesan'])->name('fetch-pesan');
    Route::get('/{konsultasiId}/status',   [ChatController::class, 'status'])->name('status');
    Route::get('/{konsultasiId}/transkrip',[ChatController::class, 'transkrip'])->name('transkrip');
});

/*
|--------------------------------------------------------------------------
| Admin Auth & Dashboard
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/keluhan', [AdminController::class, 'keluhan'])->name('keluhan.index');
    Route::get('/keluhan/{id}/chat', [AdminController::class, 'keluhanChat'])->name('keluhan.chat');
    Route::post('/keluhan/{id}/reply', [AdminController::class, 'keluhanReply'])->name('keluhan.reply');
    Route::get('/keluhan/{id}/fetch', [AdminController::class, 'keluhanFetchPesan'])->name('keluhan.fetchPesan');
    Route::post('/keluhan/{id}/selesai', [AdminController::class, 'keluhanSelesai'])->name('keluhan.selesai');
    Route::delete('/keluhan/{id}', [AdminController::class, 'destroyKeluhan'])->name('keluhan.destroy');
    Route::post('/keluhan/bulk-delete', [AdminController::class, 'bulkDestroyKeluhan'])->name('keluhan.bulkDestroy');
    Route::get('/konsultasi', [AdminController::class, 'konsultasi'])->name('konsultasi.index');
    Route::get('/konsultasi/{id}/chat', [AdminController::class, 'chat'])->name('konsultasi.chat');
    Route::post('/konsultasi/{id}/reply', [AdminController::class, 'reply'])->name('konsultasi.reply');
    Route::get('/konsultasi/{id}/fetch-pesan', [AdminController::class, 'fetchPesan'])->name('konsultasi.fetchPesan');
    Route::post('/konsultasi/{id}/akhiri', [AdminController::class, 'akhiriSesi'])->name('konsultasi.akhiri');
    Route::post('/konsultasi/{id}/mulai', [AdminController::class, 'mulaiSesi'])->name('konsultasi.mulai');
    Route::delete('/konsultasi/{id}', [AdminController::class, 'destroyKonsultasi'])->name('konsultasi.destroy');
    Route::post('/konsultasi/bulk-delete', [AdminController::class, 'bulkDestroyKonsultasi'])->name('konsultasi.bulkDestroy');
    Route::get('/pembayaran', [AdminController::class, 'pembayaran'])->name('pembayaran.index');
    Route::get('/pembayaran/export', [AdminController::class, 'exportPembayaran'])->name('pembayaran.export');
    Route::delete('/pembayaran/{id}', [AdminController::class, 'destroyPembayaran'])->name('pembayaran.destroy');
    Route::post('/pembayaran/bulk-delete', [AdminController::class, 'bulkDestroyPembayaran'])->name('pembayaran.bulkDestroy');
    Route::get('/konsultan', [AdminController::class, 'konsultan'])->name('konsultan.index');
    Route::post('/konsultan', [AdminController::class, 'storeKonsultan'])->name('konsultan.store');
    Route::put('/konsultan/{id}', [AdminController::class, 'updateKonsultan'])->name('konsultan.update');
    Route::delete('/konsultan/{id}', [AdminController::class, 'destroyKonsultan'])->name('konsultan.destroy');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/konten', [AdminController::class, 'konten'])->name('konten.index');
    Route::post('/konten/update', [AdminController::class, 'updateKonten'])->name('konten.update');
    Route::post('/konten/update-layanan', [AdminController::class, 'updateLayanan'])->name('konten.updateLayanan');
    Route::post('/konten/update-cara-kerja', [AdminController::class, 'updateCaraKerja'])->name('konten.updateCaraKerja');
    Route::get('/profil', [AdminController::class, 'editProfil'])->name('profil.edit');
    Route::put('/profil', [AdminController::class, 'updateProfil'])->name('profil.update');
});