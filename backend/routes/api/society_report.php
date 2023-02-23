<?php

use App\Http\Controllers\SocietyReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Society Report Routes
|--------------------------------------------------------------------------
*/

Route::name('society-reports')->prefix('society-reports')->group(function () {

    Route::get('/', [SocietyReportController::class, 'index']);

    Route::get('/{slug}', [SocietyReportController::class, 'show'])->name('.show');
});

Route::name('societies.reports')->prefix('{username}/society-reports')->group(function () {

    Route::get('/', [SocietyReportController::class, 'societyReports']);

    Route::get('/{slug}', [SocietyReportController::class, 'showSocietyReport'])->name('.show');

    Route::middleware(['auth:api', 'role:society'])->group(function () {

        Route::post('/', [SocietyReportController::class, 'store'])->name('.store');

        Route::put('/{societyReport}', [SocietyReportController::class, 'update'])->name('.update');

        Route::delete('/{societyReport}', [SocietyReportController::class, 'destroy'])->name('.destroy');
    });
});