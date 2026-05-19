<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingRequest;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminBookingController extends Controller
{
    public function dashboard()
    {
        $today = \Carbon\Carbon::today();

        // ── stats ──
        $stats = [
            'total'         => BookingRequest::where('status', 'confirmed')->count(),
            'today'         => BookingRequest::where('status', 'confirmed')->where('tarikh', $today->toDateString())->count(),
            'pending_users' => BookingUser::where('status', 'pending')->count(),
            'total_users'   => BookingUser::where('status', 'approved')->count(),
        ];

        // ── weekly chart (Mon–Sun of current week) ──
        $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $weeklyData = collect(range(0, 6))->map(function($i) use ($weekStart) {
            $day = $weekStart->copy()->addDays($i);
            return [
                'label' => $day->translatedFormat('D, d M'),
                'count' => BookingRequest::where('status', 'confirmed')
                            ->where('tarikh', $day->toDateString())
                            ->count(),
            ];
        });

        // ── room availability today ──
        $allBilik = \App\Models\BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get();
        $totalSlotMins = 9 * 60; // 8am-5pm

        $todayBookingsAll = BookingRequest::with('bilik')
            ->where('status', 'confirmed')
            ->where('tarikh', $today->toDateString())
            ->get();

        $bilikStatus = $allBilik->map(function($bilik) use ($todayBookingsAll, $totalSlotMins) {
            $bookings    = $todayBookingsAll->where('bilik_id', $bilik->id);
            $bookedMins  = $bookings->sum(function($b) {
                return \Carbon\Carbon::parse($b->masa_mula)->diffInMinutes(\Carbon\Carbon::parse($b->masa_tamat));
            });
            $ratio = $bookedMins / $totalSlotMins;
            return [
                'id'         => $bilik->id,
                'nama_bilik' => $bilik->nama_bilik,
                'aras'       => $bilik->aras,
                'wing'       => $bilik->wing,
                'status'     => $ratio >= 1 ? 'full' : ($ratio > 0 ? 'partial' : 'free'),
                'ratio'      => $ratio,
            ];
        });

        $bilikFree    = $bilikStatus->where('status', 'free');
        $bilikPartial = $bilikStatus->where('status', 'partial');
        $bilikFull    = $bilikStatus->where('status', 'full');

        // ── 5 most recent bookings ──
        $recentBookings = BookingRequest::with(['user', 'bilik'])
            ->where('status', 'confirmed')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $activityLogs = \App\Models\BookingActivityLog::latest()->take(10)->get();

        return view('booking.admin.dashboard', compact(
            'stats', 'weeklyData', 'today',
            'bilikFree', 'bilikPartial', 'bilikFull', 'allBilik',
            'recentBookings', 'activityLogs'
        ));
    }

    public function users(Request $request)
    {
    $query = BookingUser::with('wilayah');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users    = $query->latest()->paginate(20)->withQueryString();
        $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();

        return view('booking.admin.users', compact('users', 'wilayahs'));
    }

    public function updateUserStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        BookingUser::findOrFail($id)->update(['status' => $request->status]);

        $targetUser = BookingUser::findOrFail($id);
        $adminName  = Auth::guard('web')->user()->name;
        $statusText = $request->status === 'approved' ? 'meluluskan' : 'menolak';
        \App\Models\BookingActivityLog::log(
            'admin', $adminName,
            'updated_user_status',
            'Admin ' . $adminName . ' ' . $statusText . ' akaun pengguna ' . $targetUser->name
        );

        return back()->with('success', 'Status pengguna berjaya dikemaskini.');
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:booking_users,email,' . $id,
        'bahagian'   => 'nullable|string|max:255',
        'phone'      => 'nullable|string|max:20',
        'wilayah_id' => 'nullable|exists:wilayahs,id',
    ]);

    BookingUser::findOrFail($id)->update([
        'name'       => $request->name,
        'email'      => $request->email,
        'bahagian'   => $request->bahagian,
        'phone'      => $request->phone,
        'wilayah_id' => $request->wilayah_id,
    ]);

        $targetUser = BookingUser::findOrFail($id);
        $adminName  = Auth::guard('web')->user()->name;
        \App\Models\BookingActivityLog::log(
            'admin', $adminName,
            'edited_user',
            'Admin ' . $adminName . ' mengemaskini maklumat pengguna ' . $targetUser->name
        );

        return back()->with('success', 'Maklumat pengguna berjaya dikemaskini.');
    }

    public function deleteUser($id)
    {
        $targetUser = BookingUser::findOrFail($id);
        $adminName  = Auth::guard('web')->user()->name;
        \App\Models\BookingActivityLog::log(
            'admin', $adminName,
            'deleted_user',
            'Admin ' . $adminName . ' memadam pengguna ' . $targetUser->name
        );

        BookingUser::findOrFail($id)->delete();
        return back()->with('success', 'Pengguna berjaya dipadam.');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:booking_users,email',
            'password'   => 'required|min:8',
            'bahagian'   => 'nullable|string|max:255',
            'wilayah_id' => 'nullable|exists:wilayahs,id',
        ]);

        BookingUser::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'bahagian'   => $request->bahagian,
            'status'     => 'approved',
            'wilayah_id' => $request->wilayah_id,
        ]);

        $adminName = Auth::guard('web')->user()->name;
        \App\Models\BookingActivityLog::log(
            'admin', $adminName,
            'added_user',
            'Admin ' . $adminName . ' menambah pengguna baharu ' . $request->name
        );

        return back()->with('success', 'Pengguna berjaya ditambah.');
    }
}
