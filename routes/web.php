<?php

use App\Http\Controllers\BorangAduanKerosakanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorangMuatNaikBahanController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('index');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ict-aduan', [DashboardController::class, 'ictAduan'])->name('ict-aduan');
    Route::get('/portal-upload', [DashboardController::class, 'portalUpload'])->name('portal-upload');
    Route::get('/ict-aduan/{id}', [DashboardController::class, 'ictAduanDetail'])->name('ict-aduan.detail');
    Route::get('/portal-upload/{id}', [DashboardController::class, 'portalUploadDetail'])->name('portal-upload.detail');
    Route::post('/ict-aduan/{id}/status', [DashboardController::class, 'updateIctStatus'])->name('ict-aduan.status');
    Route::post('/ict-aduan/delete', [DashboardController::class, 'deleteIct'])->name('ict-aduan.delete');
    Route::post('/portal-upload/{id}/status', [DashboardController::class, 'updateUploadStatus'])->name('portal-upload.status');
    Route::post('/portal-upload/delete', [DashboardController::class, 'deleteUpload'])->name('portal-upload.delete');

});

//ict complaints
Route::get('/forms/ict-aduan', [BorangAduanKerosakanController::class, 'create'])->name('ict-aduan');
Route::post('/forms/ict-aduan', [BorangAduanKerosakanController::class, 'store']);

//portal upload
Route::get('/forms/portal-upload', [BorangMuatNaikBahanController::class, 'create'])->name('portal-upload');
Route::post('/forms/portal-upload', [BorangMuatNaikBahanController::class, 'store']);

require __DIR__.'/auth.php';
