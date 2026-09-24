<?php

use App\Http\Controllers\PublicReportController;
use App\Http\Controllers\PublicReportMessageController;
use App\Http\Controllers\ReportEvidenceController;
use App\Http\Controllers\ReportTrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/panduan', 'pages.guide')->name('guide');
Route::view('/privasi', 'pages.privacy')->name('privacy');

Route::controller(PublicReportController::class)->group(function (): void {
    Route::get('/lapor', 'create')->name('reports.create');
    Route::post('/lapor', 'store')->middleware('throttle:report-submissions')->name('reports.store');
    Route::get('/lapor/berhasil', 'success')->name('reports.success');
});

Route::controller(ReportTrackingController::class)->group(function (): void {
    Route::get('/status', 'create')->name('reports.track');
    Route::post('/status', 'store')->middleware('throttle:report-tracking')->name('reports.track.show');
    Route::get('/status/{report:public_code}', 'show')->name('reports.status');
});

Route::post('/status/{report:public_code}/pesan', PublicReportMessageController::class)
    ->middleware('throttle:report-messages')
    ->name('reports.messages.store');

Route::get('/admin/lampiran-laporan/{reportEvidence}/unduh', ReportEvidenceController::class)
    ->middleware('auth')
    ->name('admin.report-evidence.download');
