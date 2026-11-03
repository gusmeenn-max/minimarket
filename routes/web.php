<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManajemenController;

// Default Route
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'authenticate'])
        ->name('login.process');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::prefix('master-data')->name('master-data.')->group(function () {

        Route::resource('/kategori-barang', CategoryController::class);

        Route::resource('/daftar-barang', ProductController::class);

        Route::resource('/manajemen-pengguna', UserManajemenController::class);

    });

    Route::get('/persediaan-barang', [InventoryController::class, 'index'])
        ->name('inventory.index');

    Route::post('/persediaan-barang/transaksi', [InventoryController::class, 'storeTransaction'])
        ->name('inventory.transaction.store');

    Route::get('/laporan', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get("/profil", [ProfileController::class, "edit"])->name("profile.edit");
    Route::put("/profil", [ProfileController::class, "update"])->name("profile.update");

    Route::get("/ubah-password", [ProfileController::class, "password"])->name("profile.password");
    Route::put("/ubah-password", [ProfileController::class, "updatePassword"])->name("profile.password.update");

});

