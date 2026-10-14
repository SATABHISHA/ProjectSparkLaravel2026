<?php

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\SiteApiController;
use Illuminate\Support\Facades\Route;

Route::get('/site', [SiteApiController::class, 'show']);
Route::post('/contact', [SiteApiController::class, 'contact']);

Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::middleware('api.token')->group(function () {
    Route::get('/auth/me', [ApiAuthController::class, 'me']);
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
});

Route::prefix('admin')->middleware(['web', 'api.token', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
    Route::get('/settings', [AdminApiController::class, 'settings']);
    Route::put('/settings', [AdminApiController::class, 'updateSettings']);
    Route::get('/sections', [AdminApiController::class, 'sections']);
    Route::put('/sections/{section}', [AdminApiController::class, 'updateSection']);
    Route::get('/focus-areas', [AdminApiController::class, 'focusAreas']);
    Route::post('/focus-areas', [AdminApiController::class, 'storeFocusArea']);
    Route::put('/focus-areas/{focusArea}', [AdminApiController::class, 'updateFocusArea']);
    Route::delete('/focus-areas/{focusArea}', [AdminApiController::class, 'deleteFocusArea']);
    Route::get('/partners', [AdminApiController::class, 'partners']);
    Route::put('/partners/{partner}', [AdminApiController::class, 'updatePartner']);
    Route::get('/submissions', [AdminApiController::class, 'submissions']);
    Route::put('/submissions/{submission}', [AdminApiController::class, 'updateSubmission']);
    Route::get('/admins', [AdminApiController::class, 'admins']);
    Route::post('/admins', [AdminApiController::class, 'storeAdmin']);
    Route::put('/admins/{user}/password', [AdminApiController::class, 'updateAdminPassword']);
    Route::patch('/admins/{user}/toggle', [AdminApiController::class, 'toggleAdmin']);
});
