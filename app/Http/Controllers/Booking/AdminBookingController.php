<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingBilik;
use App\Models\BookingRequest;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminBookingController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tempahan'   => BookingRequest::count(),
            'pending'          => BookingRequest::where('status', 'pending')->count(),
            'approved'         => BookingRequest::where('status', 'approved')->count(),
            'total_users'      => BookingUser::count(),
            'pending_users'    => BookingUser::where('status', 'pending')->count(),
        ];

        $recentBookings = BookingRequest::with(['user', 'bilik'])
            ->latest()
            ->take(5)
            ->get();

        return view('booking.admin.dashboard', compact('stats', 'recentBookings'));
    }

    public function tempahan(Request $request)
    {
        $query = BookingRequest::with(['user', 'bilik']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bilik')) {
            $query->where('bilik_id', $request->bilik);
        }

        $tempahan = $query->orderBy('tarikh', 'desc')->paginate(15)->withQueryString();
        $bilikList = BookingBilik::orderBy('nama_bilik')->get();

        return view('booking.admin.tempahan', compact('tempahan', 'bilikList'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:approved,rejected',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $booking = BookingRequest::findOrFail($id);

        if ($request->status === 'approved') {
            // check conflict
            $conflict = BookingRequest::where('bilik_id', $booking->bilik_id)
                ->where('tarikh', $booking->tarikh)
                ->where('status', 'approved')
                ->where('id', '!=', $id)
                ->where(function ($q) use ($booking) {
                    $q->where('masa_mula', '<', $booking->masa_tamat)
                      ->where('masa_tamat', '>', $booking->masa_mula);
                })->exists();

            if ($conflict) {
                return back()->with('error', 'Tidak dapat meluluskan — terdapat konflik masa dengan tempahan lain.');
            }
        }

        $booking->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Status tempahan berjaya dikemaskini.');
    }

    public function users(Request $request)
    {
        $query = BookingUser::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('booking.admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:booking_users,email',
            'password' => 'required|min:8',
            'bahagian' => 'nullable|string|max:255',
        ]);

        BookingUser::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'bahagian' => $request->bahagian,
            'status'   => 'approved',
        ]);

        return back()->with('success', 'Pengguna berjaya ditambah.');
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

    public function bilik()
    {
        $bilik = BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get();
        return view('booking.admin.bilik', compact('bilik'));
    }

    public function storeBilik(Request $request)
    {
        $request->validate([
            'nama_bilik' => 'required|string|max:255|unique:booking_bilik,nama_bilik',
            'aras'       => 'required|string|max:50',
            'lokasi'     => 'nullable|string|max:100',
            'kapasiti'   => 'nullable|integer|min:0',
        ]);

        BookingBilik::create($request->only('nama_bilik', 'aras', 'lokasi', 'kapasiti'));
        return back()->with('success', 'Bilik berjaya ditambah.');
    }

    public function deleteBilik($id)
    {
        BookingBilik::findOrFail($id)->delete();
        return back()->with('success', 'Bilik berjaya dipadam.');
    }
}