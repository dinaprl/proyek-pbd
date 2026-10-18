<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/barang', [BarangController::class, 'index'])
    ->name('barang.index');

Route::get('/barang/create', [BarangController::class, 'create'])
    ->name('barang.create');

Route::post('/barang', [BarangController::class, 'store'])
    ->name('barang.store');

Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])
    ->name('barang.edit');

Route::put('/barang/{id}', [BarangController::class, 'update'])
    ->name('barang.update');

Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
    ->name('barang.destroy');


Route::get('/margin-penjualan', [MarginPenjualanController::class, 'index'])
    ->name('margin_penjualan.index');

Route::get('/margin-penjualan/create', [MarginPenjualanController::class, 'create'])
    ->name('margin_penjualan.create');

Route::post('/margin-penjualan', [MarginPenjualanController::class, 'store'])
    ->name('margin_penjualan.store');

Route::get('/margin-penjualan/{id}/edit', [MarginPenjualanController::class, 'edit'])
    ->name('margin_penjualan.edit');

Route::put('/margin-penjualan/{id}', [MarginPenjualanController::class, 'update'])
    ->name('margin_penjualan.update');

Route::delete('/margin-penjualan/{id}', [MarginPenjualanController::class, 'destroy'])
    ->name('margin_penjualan.destroy');


Route::get('/role', [RoleController::class, 'index'])
    ->name('role.index');

Route::get('/role/create', [RoleController::class, 'create'])
    ->name('role.create');

Route::post('/role', [RoleController::class, 'store'])
    ->name('role.store');

Route::get('/role/{id}/edit', [RoleController::class, 'edit'])
    ->name('role.edit');

Route::put('/role/{id}', [RoleController::class, 'update'])
    ->name('role.update');

Route::delete('/role/{id}', [RoleController::class, 'destroy'])
    ->name('role.destroy');


Route::get('/satuan', [SatuanController::class, 'index'])
    ->name('satuan.index');

Route::get('/satuan/create', [SatuanController::class, 'create'])
    ->name('satuan.create');

Route::post('/satuan', [SatuanController::class, 'store'])
    ->name('satuan.store');

Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])
    ->name('satuan.edit');

Route::put('/satuan/{id}', [SatuanController::class, 'update'])
    ->name('satuan.update');

Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])
    ->name('satuan.destroy');

Route::get('/user', [UserController::class, 'index'])
    ->name('user.index');

Route::get('/user/create', [UserController::class, 'create'])
    ->name('user.create');

Route::post('/user', [UserController::class, 'store'])
    ->name('user.store');

Route::get('/user/{id}/edit', [UserController::class, 'edit'])
    ->name('user.edit');

Route::put('/user/{id}', [UserController::class, 'update'])
    ->name('user.update');

Route::delete('/user/{id}', [UserController::class, 'destroy'])
    ->name('user.destroy');


Route::get('/vendor', [VendorController::class, 'index'])
    ->name('vendor.index');

Route::get('/vendor/create', [VendorController::class, 'create'])
    ->name('vendor.create');

Route::post('/vendor', [VendorController::class, 'store'])
    ->name('vendor.store');

Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])
    ->name('vendor.edit');

Route::put('/vendor/{id}', [VendorController::class, 'update'])
    ->name('vendor.update');

Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])
    ->name('vendor.destroy');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/', [DashboardController::class, 'index']);
