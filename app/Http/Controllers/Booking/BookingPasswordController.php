<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Mail\BrevoMailer;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingPasswordController extends Controller
{
    public function showForgot()
    {
        return view('booking.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = BookingUser::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Emel tidak dijumpai dalam sistem.');
        }

        // delete old tokens for this email
        DB::table('booking_password_resets')->where('email', $request->email)->delete();

        $token = Str::random(60);

        DB::table('booking_password_resets')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = url('/booking/reset-password/' . $token . '?email=' . urlencode($request->email));

        BrevoMailer::send(
            $user->email,
            $user->name,
            'Reset Kata Laluan — Sistem Tempahan JHS',
            view('emails.booking-reset-password', compact('user', 'resetUrl'))->render()
        );

        return back()->with('success', 'Emel reset kata laluan telah dihantar. Sila semak peti masuk anda.');
    }

    public function showReset(Request $request, $token)
    {
        return view('booking.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'token'                 => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);

        $record = DB::table('booking_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->with('error', 'Token tidak sah atau telah tamat tempoh.')->withInput();
        }

        // check token expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('booking_password_resets')->where('email', $request->email)->delete();
            return back()->with('error', 'Pautan reset telah tamat tempoh. Sila minta semula.')->withInput();
        }

        if (!Hash::check($request->token, $record->token)) {
            return back()->with('error', 'Token tidak sah.')->withInput();
        }

        $user = BookingUser::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Pengguna tidak dijumpai.')->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('booking_password_resets')->where('email', $request->email)->delete();

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'reset_password',
            $user->name . ' telah menetapkan semula kata laluan'
        );

        return redirect('/booking/calendar')->with('success', 'Kata laluan berjaya ditetapkan semula. Sila log masuk.');
    }
}