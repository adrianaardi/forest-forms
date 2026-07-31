<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Mail\BrevoMailer;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingAuthController extends Controller
{
    public function showLogin()
    {
        return view('booking.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // check booking admin
        $admin = \App\Models\User::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password) && $admin->email === 'admin.booking@sarawak.gov.my') {
            Auth::guard('web')->login($admin);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => '/booking/admin/dashboard']);
            }
            return redirect('/booking/admin/dashboard');
        }

        // check booking user
        $user = BookingUser::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->email_verified_at) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Sila sahkan emel anda terlebih dahulu melalui pautan yang dihantar.'], 422);
                }
                return back()->with('error', 'Sila sahkan emel anda terlebih dahulu melalui pautan yang dihantar.');
            }

            if ($user->status === 'rejected') {
                if ($request->ajax()) return response()->json(['message' => 'Akaun anda telah ditolak.'], 422);
                return back()->with('error', 'Akaun anda telah ditolak.');
            }
            Auth::guard('booking_user')->login($user);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => url()->current()]);
            }
            return redirect()->intended('/');
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Emel atau kata laluan tidak sah.'], 422);
        }
        return back()->with('error', 'Emel atau kata laluan tidak sah.')->withInput();
    }

    public function showRegister()
    {
        $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();
        return view('booking.daftar', compact('wilayahs'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:booking_users,email',
            'password'   => 'required|min:8|confirmed',
            'bahagian'   => 'nullable|string|max:255',
            'jawatan'    => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'wilayah_id' => 'required|exists:wilayahs,id',
        ]);

        $token = Str::random(64);

        $user = DB::transaction(function () use ($request, $token) {
            return BookingUser::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'bahagian'   => $request->bahagian,
                'jawatan'    => $request->jawatan,
                'phone'      => $request->phone,
                'wilayah_id' => $request->wilayah_id,
                'status'     => 'approved',
                'can_book'   => false,
                'email_verified_at' => null,
                'email_verification_token' => $token,
            ]);
        });

        $verifyUrl = url('/booking/verify-email/' . $token . '?email=' . urlencode($user->email));

        $mailSent = BrevoMailer::send(
            $user->email,
            $user->name,
            'Pengesahan Emel Akaun — Sistem Tempahan JHS',
            view('emails.booking-verify-email', compact('user', 'verifyUrl'))->render()
        );

        if (!$mailSent) {
            $user->delete();
            return back()->with('error', 'Gagal menghantar emel pengesahan. Sila cuba daftar semula sebentar lagi.')->withInput();
        }

        \App\Models\BookingActivityLog::log(
            'user', $request->name,
            'registered',
            $request->name . ' mendaftar akaun baharu'
        );

        return redirect('/')
            ->with('daftar_success', true);
    }

    public function verifyEmail(Request $request, string $token)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = BookingUser::where('email', $request->email)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Akaun tidak dijumpai.');
        }

        if ($user->email_verified_at) {
            return redirect('/')->with('success', 'Emel anda telah pun disahkan. Sila log masuk.');
        }

        if (!$user->email_verification_token || !hash_equals($user->email_verification_token, $token)) {
            return redirect('/')->with('error', 'Pautan pengesahan tidak sah atau telah tamat tempoh.');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'status' => 'approved',
        ]);

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'verified_email',
            $user->name . ' berjaya mengesahkan emel akaun tempahan'
        );

        return redirect('/')->with('success', 'Emel berjaya disahkan. Anda kini boleh log masuk.');
    }

    public function logout()
    {
        Auth::guard('booking_user')->logout();
        return redirect('/');
    }
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'delete_password' => 'required',
            'delete_confirm'  => 'required|in:PADAM',
        ], [
            'delete_confirm.in' => 'Sila taip "PADAM" untuk mengesahkan.',
        ]);

        $user = Auth::guard('booking_user')->user();

        if (!Hash::check($request->delete_password, $user->password)) {
            return back()->with('error', 'Kata laluan tidak sah. Akaun tidak dipadam.');
        }

        $name = $user->name;

        // Optional: delete signature file from storage if present
        if ($user->signature) {
            \Illuminate\Support\Facades\Storage::delete($user->signature);
        }

        Auth::guard('booking_user')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        \App\Models\BookingActivityLog::log(
            'user', $name,
            'deleted_account',
            $name . ' memadam akaun sendiri'
        );

        return redirect('/')->with('success', 'Akaun anda telah berjaya dipadam.');
    }

    public function logoutAdmin()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
