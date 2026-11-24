<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAdministrationController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WarehouseFlowController;

// Route Publik (Login & Lupa Kata Sandi)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::get('/lupa-kata-sandi', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/lupa-kata-sandi', [AuthController::class, 'sendResetLink'])->name('password.email');

// Route Terproteksi (Wajib Login & Akun Aktif)
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/stok', [InventoryController::class, 'index'])->name('stok');
    Route::post('/stok', [InventoryController::class, 'store'])->name('stok.store');
    Route::put('/stok/{item}', [InventoryController::class, 'update'])->name('stok.update');
    Route::delete('/stok/{item}', [InventoryController::class, 'destroy'])->name('stok.destroy');
    Route::get('/stok/export-csv', [InventoryController::class, 'exportCsv'])->name('stok.export-csv');
    Route::get('/stok/template-csv', [InventoryController::class, 'downloadTemplateCsv'])->name('stok.template-csv');
    Route::post('/stok/import-csv', [InventoryController::class, 'importCsv'])->name('stok.import-csv');

    // Barang Masuk (Inbound)
Route::get('/barang-masuk', [WarehouseFlowController::class, 'inboundIndex'])->name('barang-masuk');
Route::post('/barang-masuk', [WarehouseFlowController::class, 'inboundStore'])->name('barang-masuk.store');
Route::patch('/barang-masuk/{inbound}/status', [WarehouseFlowController::class, 'inboundUpdateStatus'])->name('barang-masuk.status');

    // Barang Keluar (Outbound)
Route::get('/barang-keluar', [WarehouseFlowController::class, 'outboundIndex'])->name('barang-keluar');
Route::post('/barang-keluar', [WarehouseFlowController::class, 'outboundStore'])->name('barang-keluar.store');
Route::patch('/barang-keluar/{outbound}/status', [WarehouseFlowController::class, 'outboundUpdateStatus'])->name('barang-keluar.status');

    // Pengiriman & Armada
Route::get('/pengiriman-armada', [WarehouseFlowController::class, 'shipmentIndex'])->name('pengiriman');
Route::post('/pengiriman-armada', [WarehouseFlowController::class, 'shipmentStore'])->name('pengiriman-armada.store');
Route::patch('/pengiriman-armada/{shipment}/status', [WarehouseFlowController::class, 'shipmentUpdateStatus'])->name('pengiriman-armada.status');
Route::patch('/armada/{fleet}/status', [WarehouseFlowController::class, 'fleetUpdateStatus'])->name('armada.status');

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