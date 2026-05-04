<?php

use App\Http\Controllers\BorangAduanKerosakanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorangMuatNaikBahanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BahagianController;
use App\Http\Controllers\Booking\AuthController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Booking\AdminBookingController;

// public booking routes
Route::prefix('booking')->name('booking.')->group(function () {

    Route::get('/', [BookingController::class, 'index'])->name('home');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar', [AuthController::class, 'register']);

    // user routes
    Route::middleware('booking.user')->group(function () {
        Route::get('/user/dashboard', [BookingController::class, 'userDashboard'])->name('user.dashboard');
        Route::get('/calendar/{bilik}', [BookingController::class, 'calendar'])->name('calendar');
        Route::get('/tempah/{bilik}', [BookingController::class, 'showTempah'])->name('tempah');
        Route::post('/tempah/{bilik}', [BookingController::class, 'storeTempah'])->name('tempah.store');
        Route::delete('/batal/{id}', [BookingController::class, 'batal'])->name('batal');
        Route::post('/user/logout', [AuthController::class, 'logoutUser'])->name('user.logout');
    });

    // admin routes
    Route::middleware('booking.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminBookingController::class, 'dashboard'])->name('dashboard');
        Route::get('/tempahan', [AdminBookingController::class, 'tempahan'])->name('tempahan');
        Route::post('/tempahan/{id}/status', [AdminBookingController::class, 'updateStatus'])->name('tempahan.status');
        Route::get('/users', [AdminBookingController::class, 'users'])->name('users');
        Route::post('/users', [AdminBookingController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{id}/status', [AdminBookingController::class, 'updateUserStatus'])->name('users.status');
        Route::delete('/users/{id}', [AdminBookingController::class, 'deleteUser'])->name('users.delete');
        Route::get('/bilik', [AdminBookingController::class, 'bilik'])->name('bilik');
        Route::post('/bilik', [AdminBookingController::class, 'storeBilik'])->name('bilik.store');
        Route::delete('/bilik/{id}', [AdminBookingController::class, 'deleteBilik'])->name('bilik.delete');
        Route::post('/logout', [AuthController::class, 'logoutAdmin'])->name('logout');
    });
});


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
