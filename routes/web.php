<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PermintaanProduksiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StokOpnameController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('users', UserController::class)->except('show');

        Route::get('/materials/data', [MaterialController::class, 'data'])->name('materials.data');
        Route::get('/materials/{material}/stock', [MaterialController::class, 'stock'])->name('materials.stock');
        Route::resource('materials', MaterialController::class)->except('show');

        Route::get('/produk/data', [ProdukController::class, 'data'])->name('produk.data');
        Route::resource('produk', ProdukController::class)->except('show');

        Route::post('/permintaan-produksi/{permintaanProduksi}/proses', [PermintaanProduksiController::class, 'proses'])->name('permintaan-produksi.proses');
        Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
    });

    Route::middleware('role:manajer')->group(function () {
        Route::get('/permintaan-produksi/create', [PermintaanProduksiController::class, 'create'])->name('permintaan-produksi.create');
        Route::post('/permintaan-produksi', [PermintaanProduksiController::class, 'store'])->name('permintaan-produksi.store');
        Route::post('/purchase-orders/{purchaseOrder}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase-orders.approve');
        Route::post('/purchase-orders/{purchaseOrder}/reject', [PurchaseOrderController::class, 'reject'])->name('purchase-orders.reject');
    });

    Route::middleware('role:staff_workshop')->group(function () {
        Route::post('/permintaan-produksi/{permintaanProduksi}/mulai', [PermintaanProduksiController::class, 'mulai'])->name('permintaan-produksi.mulai');
        Route::post('/permintaan-produksi/{permintaanProduksi}/selesai', [PermintaanProduksiController::class, 'selesai'])->name('permintaan-produksi.selesai');
        Route::post('/purchase-orders/{purchaseOrder}/terima-material', [PurchaseOrderController::class, 'terimaMaterial'])->name('purchase-orders.terima-material');
        Route::get('/stok-opname/data', [StokOpnameController::class, 'data'])->name('stok-opname.data');
        Route::resource('stok-opname', StokOpnameController::class)->only(['index', 'create', 'store']);
    });

    Route::middleware('role:admin,manajer,staff_workshop')->group(function () {
        Route::get('/permintaan-produksi/data', [PermintaanProduksiController::class, 'data'])->name('permintaan-produksi.data');
        Route::get('/permintaan-produksi', [PermintaanProduksiController::class, 'index'])->name('permintaan-produksi.index');
        Route::get('/permintaan-produksi/{permintaanProduksi}', [PermintaanProduksiController::class, 'show'])->name('permintaan-produksi.show');

        Route::get('/purchase-orders/data', [PurchaseOrderController::class, 'data'])->name('purchase-orders.data');
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
    });

    Route::middleware('role:admin,manajer')->group(function () {
        Route::get('/laporan/data', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
    });
});

Route::get('/', fn() => view('welcome'));
