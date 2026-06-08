<?php

use App\Http\Controllers\InstallerController;
use Illuminate\Support\Facades\Route;

Route::prefix('setup')->name('installer.')->group(function () {
    Route::get('/',        [InstallerController::class, 'show'])->name('show');
    Route::post('/',       [InstallerController::class, 'install'])->name('install');
    Route::get('/complete',[InstallerController::class, 'complete'])->name('complete');
});
