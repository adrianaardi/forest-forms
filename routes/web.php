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
use Illuminate\Support\Facades\Route;

// ── Homepage ──────────────────────────────────────────────
Route::get('/', fn() => view('index'));

// ── JHS Admin ─────────────────────────────────────────────
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // ict aduan
    Route::get('/ict-aduan',             [DashboardController::class, 'ictAduan'])->name('ict-aduan');
    Route::get('/ict-aduan/{id}',        [DashboardController::class, 'ictAduanDetail'])->name('ict-aduan.detail');
    Route::post('/ict-aduan/{id}/status',[DashboardController::class, 'updateIctStatus'])->name('ict-aduan.status');
    Route::post('/ict-aduan/delete',     [DashboardController::class, 'deleteIct'])->name('ict-aduan.delete');

    // portal upload
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

    return view('track', compact('result', 'type', 'tiket'));
})->name('track.search');

// ── Supervisor Review ─────────────────────────────────────
Route::get('/semak/{token}',  [BorangMuatNaikBahanController::class, 'supervisorView'])->name('supervisor.view');
Route::post('/semak/{token}', [BorangMuatNaikBahanController::class, 'supervisorApprove'])->name('supervisor.approve');

// ── Booking System ────────────────────────────────────────
Route::prefix('booking')->name('booking.')->group(function () {

    // public
    Route::get('/',               [BookingController::class, 'index'])->name('home');
    Route::get('/calendar',       [BookingController::class, 'calendar'])->name('calendar');
    Route::get('/cancel/{token}', [BookingController::class, 'cancelBooking'])->name('cancel');
    Route::get('/book/{bilik}',   [BookingController::class, 'showBook'])->name('book');
    Route::post('/book/{bilik}',  [BookingController::class, 'storeBook'])->name('book.store');

    // auth
    Route::get('/login',  [BookingAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [BookingAuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [BookingAuthController::class, 'showRegister'])->name('daftar');
    Route::post('/daftar',[BookingAuthController::class, 'register'])->name('daftar.post');
    Route::post('/logout',[BookingAuthController::class, 'logout'])->name('logout');

    // booking admin
    Route::middleware('booking.admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard',          [AdminBookingController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',              [AdminBookingController::class, 'users'])->name('users');
        Route::post('/users/{id}/status', [AdminBookingController::class, 'updateUserStatus'])->name('users.status');
        Route::delete('/users/{id}',      [AdminBookingController::class, 'deleteUser'])->name('users.delete');
        Route::post('/logout',            [BookingAuthController::class, 'logoutAdmin'])->name('logout');
    });
});

// ── Test Mail ─────────────────────────────────────────────
Route::get('/test-mail', function () {
    $result = \App\Mail\BrevoMailer::send(
        'vienneblue@email.com',
        'Test',
        'Test dari Railway',
        '<p>Ini adalah emel ujian dari Railway.</p>'
    );
    return $result ? 'Sent!' : 'Failed — check logs';
});

// ── Breeze Auth ───────────────────────────────────────────
require __DIR__ . '/auth.php';