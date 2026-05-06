<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BookingUserProfileController extends Controller
{
    private function guard()
    {
        return Auth::guard('booking_user');
    }

    public function index()
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }
        $user = $this->guard()->user();
        return view('booking.user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $user = $this->guard()->user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:booking_users,email,' . $user->id,
            'bahagian' => 'nullable|string|max:255',
        ]);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->bahagian = $request->bahagian;
        $user->save();

        return back()->with('success', 'Profil berjaya dikemaskini.');
    }

    public function updatePassword(Request $request)
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $user = $this->guard()->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata laluan semasa tidak betul.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata laluan berjaya dikemaskini.');
    }
}