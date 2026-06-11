<?php

use App\Http\Controllers\BorangAduanKerosakanController;
use App\Http\Controllers\BorangMuatNaikBahanController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BahagianController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Booking\BookingAuthController;
use App\Http\Controllers\Booking\AdminBookingController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\AktivitiController;
use App\Http\Controllers\Admin\DisplayController;
use App\Http\Controllers\Admin\PergerakanDashboardController;
use Illuminate\Support\Facades\Route;

// ── Homepage ──────────────────────────────────────────────
Route::get('/', fn() => view('index'));
Route::get('/display/pergerakan', [DisplayController::class, 'pergerakan'])->name('display.pergerakan');
Route::get('/display/full-display', [DisplayController::class, 'fullDisplay'])->name('display.full-display');

// ── JHS Admin ─────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ict aduan
    Route::get('/ict-aduan',             [DashboardController::class, 'ictAduan'])->name('ict-aduan');
    Route::get('/ict-aduan/{id}',        [DashboardController::class, 'ictAduanDetail'])->name('ict-aduan.detail');
    Route::post('/ict-aduan/{id}/status',[DashboardController::class, 'updateIctStatus'])->name('ict-aduan.status');
    Route::post('/ict-aduan/delete',     [DashboardController::class, 'deleteIct'])->name('ict-aduan.delete');
    Route::get('/dashboard-ict', [DashboardController::class, 'dashboardIct'])->name('dashboard-ict');
    
    // portal upload
    Route::get('/dashboard-mohon', [DashboardController::class, 'dashboardMohon'])->name('dashboard-mohon');
    Route::get('/portal-upload',              [DashboardController::class, 'portalUpload'])->name('portal-upload');
    Route::get('/portal-upload/{id}',         [DashboardController::class, 'portalUploadDetail'])->name('portal-upload.detail');
    Route::post('/portal-upload/{id}/status', [DashboardController::class, 'updateUploadStatus'])->name('portal-upload.status');
    Route::post('/portal-upload/delete',      [DashboardController::class, 'deleteUpload'])->name('portal-upload.delete');
    Route::post('/portal-upload/resend',      [DashboardController::class, 'resendSupervisorEmail'])->name('portal-upload.resend');

    // profile
    Route::get('/profile',          [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/password',[ProfileController::class, 'updatePassword'])->name('profile.password');

    // accounts
    Route::get('/accounts',         [AccountController::class, 'index'])->name('accounts');
    Route::post('/accounts',        [AccountController::class, 'store'])->name('accounts.store');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // bahagian supervisor
    Route::get('/bahagian',         [BahagianController::class, 'index'])->name('bahagian');
    Route::post('/bahagian',        [BahagianController::class, 'store'])->name('bahagian.store');
    Route::delete('/bahagian/{id}', [BahagianController::class, 'destroy'])->name('bahagian.destroy');

    Route::prefix('pergerakan-pegawai')->name('pergerakan.')->group(function () {
        
        Route::get('/', [PergerakanDashboardController::class, 'index'])
        ->name('index');

        // 2. Super Admin Actions
        Route::post('/bahagian', [BahagianController::class, 'storeBahagian'])->name('bahagian.store');
        Route::post('/subadmin', [BahagianController::class, 'storeSubAdmin'])->name('subadmin.store');

        // 3. Sub-Admin Actions (Officer Roster)
        Route::post('/pegawai', [PegawaiController::class, 'storePegawai'])->name('pegawai.store');
        Route::patch('/pegawai/{id}/toggle', [PegawaiController::class, 'toggleAttendance'])->name('pegawai.toggle');
        Route::patch('/pegawai/{id}/remarks', [PegawaiController::class, 'updateRemarks'])->name('pegawai.updateRemarks');
        
        // 4. Sub-Admin Actions (Activity Logs)
        Route::post('/aktiviti', [AktivitiController::class, 'storeAktiviti'])->name('aktiviti.store');
    });
});

// ── Public Forms ──────────────────────────────────────────
Route::get('/forms/ict-aduan',   [BorangAduanKerosakanController::class, 'create'])->name('ict-aduan');
Route::post('/forms/ict-aduan',  [BorangAduanKerosakanController::class, 'store']);

Route::get('/forms/portal-upload',  [BorangMuatNaikBahanController::class, 'create'])->name('portal-upload');
Route::post('/forms/portal-upload', [BorangMuatNaikBahanController::class, 'store']);

// ── Ticket Tracking ───────────────────────────────────────
Route::get('/semak-tiket', fn() => view('track'))->name('track');
Route::post('/semak-tiket', function (\Illuminate\Http\Request $request) {
    $tiket  = strtoupper(trim($request->no_tiket));
    $result = null;
    $type   = null;

    preg_match('/\((0*)(\d+)\)$/', $tiket, $matches);
    $id = isset($matches[2]) ? (int) $matches[2] : null;

    if ($id) {
        if (str_contains($tiket, '/ICT/')) {
            $record = \App\Models\BorangAduanKerosakan::find($id);
            if ($record && $record->no_tiket === $tiket) {
                $result = $record;
                $type   = 'ict';
            }
        } elseif (str_contains($tiket, '/MNB/')) {
            $record = \App\Models\BorangMuatNaikBahan::find($id);
            if ($record && $record->no_tiket === $tiket) {
                $result = $record;
                $type   = 'mnb';
            }
        }
    }

    return view('index', compact('result', 'type', 'tiket'));
})->name('track.search');

// ── Supervisor Review ─────────────────────────────────────
Route::get('/semak/{token}',  [BorangMuatNaikBahanController::class, 'supervisorView'])->name('supervisor.view');
Route::post('/semak/{token}', [BorangMuatNaikBahanController::class, 'supervisorApprove'])->name('supervisor.approve');

// ── Booking System ────────────────────────────────────────
Route::prefix('booking')->name('booking.')->group(function () {

    // public
    Route::get('/',               [BookingController::class, 'index'])->name('home');
    Route::get('/calendar',       [BookingController::class, 'calendar'])->name('calendar');
    Route::post('/cancel/{token}', [BookingController::class, 'cancelBooking'])->name('cancel');    
    Route::get('/book/{bilik?}', [BookingController::class, 'showBook'])->name('book');
    Route::post('/book', [BookingController::class, 'storeBook'])->name('book.store');

    // auth
    Route::get('/login', fn() => redirect('/booking/calendar'))->name('login');
    Route::post('/login', [BookingAuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [BookingAuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar',[BookingAuthController::class, 'register'])->name('daftar.post');
    Route::post('/logout',[BookingAuthController::class, 'logout'])->name('logout');
    Route::get('/profile',           [\App\Http\Controllers\Booking\BookingUserProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile',          [\App\Http\Controllers\Booking\BookingUserProfileController::class, 'update'])->name('user.profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Booking\BookingUserProfileController::class, 'updatePassword'])->name('user.profile.password');
    Route::get('/my-bookings', [\App\Http\Controllers\Booking\BookingController::class, 'myBookings'])->name('my-bookings');
    
    // password reset
    Route::get('/forgot-password',        [\App\Http\Controllers\Booking\BookingPasswordController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password',       [\App\Http\Controllers\Booking\BookingPasswordController::class, 'sendReset'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Booking\BookingPasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', 
           [\App\Http\Controllers\Booking\BookingPasswordController::class, 'resetPassword'])->name('password.store');    
    // booking admin
    Route::middleware('booking.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',          [AdminBookingController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',              [AdminBookingController::class, 'users'])->name('users');
        Route::post('/users/{id}/status', [AdminBookingController::class, 'updateUserStatus'])->name('users.status');
        Route::delete('/users/{id}',      [AdminBookingController::class, 'deleteUser'])->name('users.delete');
        Route::post('/users/{id}/edit', [AdminBookingController::class, 'editUser'])->name('users.edit');
        Route::post('/logout',            [BookingAuthController::class, 'logoutAdmin'])->name('logout');
        Route::post('/users', [AdminBookingController::class, 'storeUser'])->name('users.store');
        
    });

    
});

// ── Breeze Auth ───────────────────────────────────────────
require __DIR__ . '/auth.php';