<?php

use App\Http\Controllers\BorangAduanKerosakanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorangMuatNaikBahanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BahagianController;


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
Route::post('/portal-upload/resend', [DashboardController::class, 'resendSupervisorEmail'])->name('portal-upload.resend');    
    //profiles
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    //supervisor
    Route::get('/bahagian', [BahagianController::class, 'index'])->name('bahagian');
    Route::post('/bahagian', [BahagianController::class, 'store'])->name('bahagian.store');
    Route::delete('/bahagian/{id}', [BahagianController::class, 'destroy'])->name('bahagian.destroy');

});

//ict complaints
Route::get('/forms/ict-aduan', [BorangAduanKerosakanController::class, 'create'])->name('ict-aduan');
Route::post('/forms/ict-aduan', [BorangAduanKerosakanController::class, 'store']);

//portal upload
Route::get('/forms/portal-upload', [BorangMuatNaikBahanController::class, 'create'])->name('portal-upload');
Route::post('/forms/portal-upload', [BorangMuatNaikBahanController::class, 'store']);

//ticket checking
Route::get('/semak-tiket', function() {
    return view('track');
})->name('track');

Route::post('/semak-tiket', function(\Illuminate\Http\Request $request) {
    $tiket = strtoupper(trim($request->no_tiket));
    $result = null;
    $type = null;

    // Use RegEx to extract only the numbers inside the brackets at the end
    // This turns "JHS/MNB/A/2026(012)" into "012", and (int) makes it 12
    preg_match('/\((0*)(\d+)\)$/', $tiket, $matches);
    $id = isset($matches[2]) ? (int)$matches[2] : null;

    if ($id) {
        if (str_contains($tiket, '/ICT/')) {
            $record = \App\Models\BorangAduanKerosakan::find($id);
            // Verify if the formatted ticket matches what the user typed
            if ($record && $record->no_tiket === $tiket) {
                $result = $record;
                $type = 'ict';
            }
        } elseif (str_contains($tiket, '/MNB/')) {
            $record = \App\Models\BorangMuatNaikBahan::find($id);
            if ($record && $record->no_tiket === $tiket) {
                $result = $record;
                $type = 'mnb';
            }
        }
    }

    return view('track', compact('result', 'type', 'tiket'));
})->name('track.search');

//supervisor check
Route::get('/semak/{token}', [BorangMuatNaikBahanController::class, 'supervisorView'])->name('supervisor.view');
Route::post('/semak/{token}', [BorangMuatNaikBahanController::class, 'supervisorApprove'])->name('supervisor.approve');

//testing email
Route::get('/test-mail', function() {
    \Illuminate\Support\Facades\Mail::raw('Test emel dari Railway', function($msg) {
        $msg->to('vienneblue@email.com')->subject('Test');
    });
    return 'Sent!';
});
require __DIR__.'/auth.php';
