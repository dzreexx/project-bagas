<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAdministrationController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

// Route Publik (Login & Lupa Kata Sandi)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/lupa-kata-sandi', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/lupa-kata-sandi', [AuthController::class, 'sendResetLink'])->name('password.email');

// Route Terproteksi (Wajib Login & Akun Aktif)
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/stok', fn () => view('stok'))->name('stok');
    Route::get('/barang-masuk', fn () => view('barang-masuk'))->name('barang-masuk');
    Route::get('/barang-keluar', fn () => view('barang-keluar'))->name('barang-keluar');
    Route::get('/pengiriman', fn () => view('pengiriman'))->name('pengiriman');
    Route::get('/laporan', fn () => view('laporan'))->name('laporan');
    
    Route::get('/pengaturan', [SettingsController::class, 'index'])->name('pengaturan');
    Route::put('/pengaturan/profil', [SettingsController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::put('/pengaturan/password', [SettingsController::class, 'updatePassword'])->name('pengaturan.password');
    Route::put('/pengaturan/tampilan', [SettingsController::class, 'updateAppearance'])->name('pengaturan.appearance');

    Route::get('/user-administration', [UserAdministrationController::class, 'index'])
        ->name('user-administration');
    Route::post('/user-administration', [UserAdministrationController::class, 'store'])
        ->name('user-administration.store');
    Route::patch('/user-administration/{user}/toggle-status', [UserAdministrationController::class, 'toggleStatus'])
        ->name('user-administration.toggle-status');

    Route::post('/notifications/{log}/read', function (ActivityLog $log) {
        $log->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    })->name('notifications.read');

    Route::post('/notifications/read-all', function () {
        ActivityLog::where('is_read', false)->update(['is_read' => true]);
        return response()->json(['ok' => true]);
    })->name('notifications.read-all');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});