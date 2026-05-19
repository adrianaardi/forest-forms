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
        $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();
        return view('booking.user.profile', compact('user', 'wilayahs'));
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
            'phone'    => 'nullable|string|max:20',
            'wilayah_id' => 'required|exists:wilayahs,id',
        ]);

        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->bahagian = $request->bahagian;
        $user->phone    = $request->phone;
        $user->wilayah_id = $request->wilayah_id;
        $user->save();
        
        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'updated_profile',
            $user->name . ' mengemaskini maklumat profil'
        );

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

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'updated_password',
            $user->name . ' menukar kata laluan'
        );

        return back()->with('success', 'Kata laluan berjaya dikemaskini.');
    }
}