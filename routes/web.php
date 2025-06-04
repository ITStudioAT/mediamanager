<?php

use Illuminate\Support\Facades\Route;
use Itstudioat\Mediamanager\src\Http\Controllers\MediamanagerController;

// Globales Throttle
Route::prefix('mediamanager')->middleware(['web', 'throttle:global', 'throttle:api'])->group(function () {
    Route::get('/download',  [MediamanagerController::class, 'download']);
    Route::get('/download_folder',  [MediamanagerController::class, 'downloadFolder']);
});
