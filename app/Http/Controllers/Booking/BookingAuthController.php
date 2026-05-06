<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // check booking admin first
        $admin = \App\Models\User::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password) && $admin->email === 'admin.booking@sarawak.gov.my') {
            Auth::guard('web')->login($admin);
            return redirect('/booking/admin/dashboard');
        }

        // check booking user
        $user = BookingUser::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status === 'pending') {
                return back()->with('error', 'Akaun anda masih menunggu kelulusan admin.');
            }
            if ($user->status === 'rejected') {
                return back()->with('error', 'Akaun anda telah ditolak. Hubungi admin untuk maklumat lanjut.');
            }
            Auth::guard('booking_user')->login($user);
            return redirect()->intended('/booking/calendar');
        }

        return back()->with('error', 'Emel atau kata laluan tidak sah.')->withInput();
    }

    public function showRegister()
    {
        return view('booking.daftar');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:booking_users,email',
            'password' => 'required|min:8|confirmed',
            'bahagian' => 'nullable|string|max:255',
        ]);

        BookingUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'bahagian' => $request->bahagian,
            'status'   => 'pending',
        ]);

        return redirect('/booking/login')
            ->with('success', 'Pendaftaran berjaya! Sila tunggu kelulusan admin sebelum log masuk.');
    }

    public function logout()
    {
        Auth::guard('booking_user')->logout();
        return redirect('/');
    }

    public function logoutAdmin()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}