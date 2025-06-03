<?php

use Illuminate\Support\Facades\Route;
use Itstudioat\Mediamanager\src\Http\Controllers\MediamanagerController;

// Globales Throttle
Route::prefix('api/mediamanager')->middleware(['web', 'throttle:global', 'throttle:api'])->group(function () {
    Route::get('/folder_structure',  [MediamanagerController::class, 'folderStructure']);
    Route::get('/create_preview',  [MediamanagerController::class, 'createPreview']);
    Route::get('/csrf',  [MediamanagerController::class, 'csrf']);
    Route::post('/upload',  [MediamanagerController::class, 'uploadPost']);
    Route::patch('/upload',  [MediamanagerController::class, 'uploadPatch']);
    Route::delete('/upload', [MediaController::class, 'uploadDelete']);
    Route::get('/upload', [MediaController::class, 'uploadGet']);

    Route::post('/destroy_files',  [MediamanagerController::class, 'destroyFiles']);
});
