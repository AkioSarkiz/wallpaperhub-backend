<?php

declare(strict_types=1);

use App\Http\Controllers\TrackController;
use App\Http\Controllers\WallpaperController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('wallpapers/search', [WallpaperController::class, 'search']);
    Route::apiResource('wallpapers', WallpaperController::class)->only(['index', 'show']);

    Route::post('tracks/{tableName}/{tableId}/{eventName}', [TrackController::class, 'store']);
});
