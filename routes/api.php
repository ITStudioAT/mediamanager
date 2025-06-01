<?php

use Illuminate\Support\Facades\Route;
use Itstudioat\Mediamanager\src\Http\Controllers\MediamanagerController;

// Globales Throttle
Route::prefix('api/mediamanager')->middleware(['throttle:global', 'throttle:api'])->group(function () {
    Route::get('/folder_structure',  [MediamanagerController::class, 'folderStructure']);
    Route::get('/create_preview',  [MediamanagerController::class, 'createPreview']);
});
