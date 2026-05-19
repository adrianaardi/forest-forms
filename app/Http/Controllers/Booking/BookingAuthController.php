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
            if ($user->status === 'pending') {
                if ($request->ajax()) return response()->json(['message' => 'Akaun anda masih menunggu kelulusan admin.'], 422);
                return back()->with('error', 'Akaun anda masih menunggu kelulusan admin.');
            }
            if ($user->status === 'rejected') {
                if ($request->ajax()) return response()->json(['message' => 'Akaun anda telah ditolak.'], 422);
                return back()->with('error', 'Akaun anda telah ditolak.');
            }
            Auth::guard('booking_user')->login($user);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'redirect' => url()->current()]);
            }
            return redirect()->intended('/booking/calendar');
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
            'phone'      => 'nullable|string|max:20',
            'wilayah_id' => 'required|exists:wilayahs,id',
        ]);

        BookingUser::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'bahagian'   => $request->bahagian,
            'phone'      => $request->phone,
            'wilayah_id' => $request->wilayah_id,
            'status'     => 'pending',
        ]);

        \App\Models\BookingActivityLog::log(
            'user', $request->name,
            'registered',
            $request->name . ' mendaftar akaun baharu'
        );

        return redirect('/booking/calendar')
            ->with('daftar_success', true);
    }

    public function logout()
    {
        Auth::guard('booking_user')->logout();
        return redirect('/booking/calendar');
    }

    public function logoutAdmin()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
