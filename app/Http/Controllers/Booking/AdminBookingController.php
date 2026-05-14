<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminBookingController extends Controller
{
    public function dashboard()
    {
        $today = \Carbon\Carbon::today();
        $stats = [
            'total'          => BookingRequest::where('status', 'confirmed')->count(),
            'today'          => BookingRequest::where('status', 'confirmed')->where('tarikh', $today)->count(),
            'this_week'      => BookingRequest::where('status', 'confirmed')
                                ->whereBetween('tarikh', [$today->startOfWeek(), $today->copy()->endOfWeek()])->count(),
            'total_users'    => BookingUser::where('status', 'approved')->count(),
            'pending_users'  => BookingUser::where('status', 'pending')->count(),
            'total_bilik'    => \App\Models\BookingBilik::count(),
        ];

        $recentBookings = BookingRequest::with(['user', 'bilik'])
            ->where('status', 'confirmed')
            ->where('tarikh', '>=', \Carbon\Carbon::today())
            ->orderBy('tarikh')
            ->orderBy('masa_mula')
            ->take(10)
            ->get();

        $todayBookings = BookingRequest::with(['user', 'bilik'])
            ->where('status', 'confirmed')
            ->where('tarikh', \Carbon\Carbon::today()->toDateString())
            ->orderBy('masa_mula')
            ->get();

        return view('booking.admin.dashboard', compact('stats', 'recentBookings', 'todayBookings'));
    }

    public function users(Request $request)
    {
        $query = BookingUser::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('booking.admin.users', compact('users'));
    }

    public function updateUserStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        BookingUser::findOrFail($id)->update(['status' => $request->status]);
        return back()->with('success', 'Status pengguna berjaya dikemaskini.');
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:booking_users,email,' . $id,
            'bahagian' => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
        ]);

        BookingUser::findOrFail($id)->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'bahagian' => $request->bahagian,
            'phone'    => $request->phone,
        ]);

        return back()->with('success', 'Maklumat pengguna berjaya dikemaskini.');
    }

    public function deleteUser($id)
    {
        BookingUser::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berjaya dipadam.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:booking_users,email',
            'password' => 'required|min:8',
            'bahagian' => 'nullable|string|max:255',
        ]);

        \App\Models\BookingUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'bahagian' => $request->bahagian,
            'status'   => 'approved',
        ]);

        return back()->with('success', 'Pengguna berjaya ditambah.');
    }
}
