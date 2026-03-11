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
