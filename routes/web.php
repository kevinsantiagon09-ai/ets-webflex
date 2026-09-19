<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::get('/', function (): Response {
    return Inertia::render('Home', [
        'loginUrl' => route('login'),
        'adminUrl' => route('admin'),
    ]);
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', EnsureUserIsAdmin::class])->group(function (): void {
    Route::post('/admin/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/banner', [BannerController::class, 'index'])->name('banner.index');
    Route::get('/admin', [BannerController::class, 'index'])->name('admin');
    Route::get('/admin/banners/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('/admin/banners', [BannerController::class, 'store'])->name('banners.store');
});
