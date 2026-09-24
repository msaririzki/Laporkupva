<?php

use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\ReportTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::controller(PublicReportController::class)->group(function (): void {
    Route::get('/lapor', 'create')->name('reports.create');
    Route::post('/lapor', 'store')->middleware('throttle:report-submissions')->name('reports.store');
    Route::get('/lapor/berhasil', 'success')->name('reports.success');
});

Route::controller(ReportTrackingController::class)->group(function (): void {
    Route::get('/status', 'create')->name('reports.track');
    Route::post('/status', 'store')->middleware('throttle:report-tracking')->name('reports.track.show');
});
