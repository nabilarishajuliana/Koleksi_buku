<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\OTPController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BarangController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//dashboard
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});

//pages
Route::middleware(['auth'])->group(function () {

    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    Route::resource('buku', App\Http\Controllers\BukuController::class);
});


Route::get('/report/buku', [ReportController::class, 'bukuLandscape'])
    ->middleware('auth')
    ->name('report.buku');

Route::get('/report/undangan', [ReportController::class, 'undanganPortrait'])
    ->middleware('auth')
    ->name('report.undangan');

//google
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

//otp
Route::get('/verify-otp', [OTPController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OTPController::class, 'verify'])->name('otp.verify');



Route::get('/barang',                [BarangController::class, 'index'])  ->name('barang.index');
Route::get('/barang/create',         [BarangController::class, 'create']) ->name('barang.create');
Route::post('/barang',               [BarangController::class, 'store'])  ->name('barang.store');
Route::get('/barang/{barang}/edit',  [BarangController::class, 'edit'])   ->name('barang.edit');
Route::put('/barang/{barang}',       [BarangController::class, 'update']) ->name('barang.update');
Route::delete('/barang/{barang}',    [BarangController::class, 'destroy'])->name('barang.destroy');

Route::get('/barang/cetak', [BarangController::class, 'cetakIndex'])->name('barang.cetak.index');

Route::post('/barang/cetak', [BarangController::class, 'cetak'])->name('barang.cetak');

// =====================
// STUDI KASUS - JS
// =====================
Route::get('/js/tabel-biasa',      [App\Http\Controllers\JsController::class, 'tabelBiasa'])      ->name('js.tabel_biasa')      ->middleware('auth');
Route::get('/js/tabel-datatables', [App\Http\Controllers\JsController::class, 'tabelDatatables']) ->name('js.tabel_datatables') ->middleware('auth');
Route::get('/js/sc4-select', [App\Http\Controllers\JsController::class, 'sc4Select'])
    ->name('js.sc4_select')
    ->middleware('auth');
// Route::resource('barang', controller: BarangController::class);

// =============================================
// SC1: Wilayah Indonesia
// =============================================

// Halaman tampilan
Route::get('/ajax/wilayah', [App\Http\Controllers\WilayahController::class, 'index'])
    ->name('ajax.wilayah')
    ->middleware('auth');

// API endpoint wilayah (GET, tidak butuh CSRF token)
Route::get('/api/provinsi',            [App\Http\Controllers\WilayahController::class, 'provinsi'])->name('api.provinsi');
Route::get('/api/kota/{id_provinsi}',  [App\Http\Controllers\WilayahController::class, 'kota']);
Route::get('/api/kecamatan/{id_kota}', [App\Http\Controllers\WilayahController::class, 'kecamatan']);
Route::get('/api/kelurahan/{id_kec}',  [App\Http\Controllers\WilayahController::class, 'kelurahan']);


// =============================================
// SC2: POS / Kasir
// =============================================

Route::get('/ajax/pos', [App\Http\Controllers\PosController::class, 'index'])
    ->name('ajax.pos')
    ->middleware('auth');

// Tambahkan ini (route baru untuk versi Axios):
Route::get('/ajax/pos-axios', [App\Http\Controllers\PosController::class, 'indexAxios'])
    ->name('ajax.pos.axios')
    ->middleware('auth');

Route::post('/api/pos/cari-barang', [App\Http\Controllers\PosController::class, 'cariBarang'])
    ->name('api.pos.cari');

Route::post('/api/pos/bayar', [App\Http\Controllers\PosController::class, 'bayar'])
    ->name('api.pos.bayar');

// =============================================
// PAYMENT GATEWAY - Customer
// =============================================

// Halaman pemesanan (tidak perlu auth/login)
Route::get('/pesan', [App\Http\Controllers\CustomerController::class, 'index'])
    ->name('customer.pesan');

// AJAX: ambil menu by vendor
Route::get('/api/menu/{id_vendor}', [App\Http\Controllers\CustomerController::class, 'getMenu'])
    ->name('api.menu');

// AJAX: proses checkout & generate token Midtrans
Route::post('/api/checkout', [App\Http\Controllers\CustomerController::class, 'checkout'])
    ->name('api.checkout');

// Webhook Midtrans — HARUS di luar middleware auth & CSRF
Route::post('/webhook/midtrans', [App\Http\Controllers\CustomerController::class, 'webhook'])
    ->name('webhook.midtrans');



// =============================================
// VENDOR AUTH
// =============================================
Route::get('/vendor/login',  [App\Http\Controllers\VendorAuthController::class, 'showLogin'])->name('vendor.login');
Route::post('/vendor/login', [App\Http\Controllers\VendorAuthController::class, 'login'])->name('vendor.login.post');
Route::post('/vendor/logout',[App\Http\Controllers\VendorAuthController::class, 'logout'])->name('vendor.logout');

// =============================================
// VENDOR DASHBOARD (proteksi pakai vendor.auth)
// =============================================
Route::middleware(['vendor.auth'])->group(function () {
    Route::get('/vendor/dashboard',    [App\Http\Controllers\VendorController::class, 'index'])->name('vendor.dashboard');
    Route::post('/vendor/menu',        [App\Http\Controllers\VendorController::class, 'storeMenu'])->name('vendor.menu.store');
    Route::delete('/vendor/menu/{id}', [App\Http\Controllers\VendorController::class, 'destroyMenu'])->name('vendor.menu.destroy');
});