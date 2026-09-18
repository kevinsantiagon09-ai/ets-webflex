<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');

Route::get('/admin', [BannerController::class, 'index'])->name('admin');
Route::post('/admin/banners', [BannerController::class, 'store'])->name('banners.store');
