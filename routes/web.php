<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route tentang
Route::get('/tentang', function () {
    return view('tentang');
});
// route di isi manual nama nya
Route::get('/sapa/{nama}', function ($nama) {
    return "Halo, $nama! Selamat datang di Toko Online.";
});
// route dengan default parameter antisipasi kalo ga di isi
Route::get('/kategori/{nama?}', function ($nama = 'Semua') {
    return "Menampilkan kategori: $nama";
});
Route::get('/produk/{id}', function ($id) {
    return "Detail produk #$id";
})->name('produk.detail');