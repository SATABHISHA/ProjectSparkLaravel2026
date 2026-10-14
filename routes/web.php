<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminPanelController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', SiteController::class)->name('home');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'loginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('forgot-password', [AdminAuthController::class, 'forgotForm'])->name('admin.password.request');
    Route::post('forgot-password', [AdminAuthController::class, 'forgot'])->name('admin.password.email');
    Route::get('reset-password/{token}', [AdminAuthController::class, 'resetForm'])->name('admin.password.reset');
    Route::post('reset-password', [AdminAuthController::class, 'reset'])->name('admin.password.update');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', AdminPanelController::class)->name('admin.dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });
});
