<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 *
 * Here is where you can register web routes for your application.
 * These routes are typically stateless and are not protected by any middleware.
 * However, you can apply middleware to these routes if needed.
 *
 * @see https://laravel.com/docs/routing
 */

/**
 * Route to export a file
 * This route is signed, meaning it requires a valid signature to access.
 * The filename is passed as a parameter and is used to locate the file in the storage.
 */
Route::get('/exported/file/{filename}', function ($filename) {
    $filePath = "public/exported/$filename";
    if (!Storage::disk('local')->exists($filePath)) {
        abort(404);
    }
    return response()->file(storage_path("app/private/$filePath"));
})->name('exported.file')->middleware('signed');