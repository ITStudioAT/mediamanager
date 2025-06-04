<?php

use Illuminate\Support\Facades\Route;
use Itstudioat\Mediamanager\src\Http\Controllers\MediamanagerController;


// Globales Throttle
Route::prefix('api/mediamanager')->middleware(['web', 'throttle:global', 'throttle:api'])->group(function () {
    Route::get('/folder_structure',  [MediamanagerController::class, 'folderStructure']);
    Route::get('/create_preview',  [MediamanagerController::class, 'createPreview']);
    Route::get('/csrf',  [MediamanagerController::class, 'csrf']);
    /*
    Route::post('/upload',  [MediamanagerController::class, 'upload']);
*/
    Route::post('/upload_filepond',  [MediamanagerController::class, 'uploadPost']);
    Route::patch('/upload_filepond',  [MediamanagerController::class, 'uploadPatch']);
    Route::delete('/upload_filepond', [MediamanagerController::class, 'uploadDelete']);
    Route::get('/upload_filepond', [MediamanagerController::class, 'uploadGet']);


    Route::post('/destroy_files',  [MediamanagerController::class, 'destroyFiles']);
    Route::post('/save_filename',  [MediamanagerController::class, 'saveFilename']);
    Route::post('/destroy_folder',  [MediamanagerController::class, 'destroyFolder']);
});
