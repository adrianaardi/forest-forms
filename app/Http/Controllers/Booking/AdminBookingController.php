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
        $stats = [
            'total'         => BookingRequest::where('status', 'confirmed')->count(),
            'today'         => BookingRequest::where('status', 'confirmed')->where('tarikh', today())->count(),
            'total_users'   => BookingUser::count(),
            'pending_users' => BookingUser::where('status', 'pending')->count(),
        ];

        $recentBookings = BookingRequest::with(['user', 'bilik'])
            ->where('status', 'confirmed')
            ->where('tarikh', '>=', today())
            ->orderBy('tarikh')
            ->orderBy('masa_mula')
            ->take(5)
            ->get();

        return view('booking.admin.dashboard', compact('stats', 'recentBookings'));
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

    public function deleteUser($id)
    {
        BookingUser::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berjaya dipadam.');
    }
}