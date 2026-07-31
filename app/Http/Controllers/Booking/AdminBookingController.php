<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingActivityLog;
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
        
        // Filter for Ibu Pejabat only
        $wilayah = \App\Models\Wilayah::where('nama_wilayah', 'Ibu Pejabat')->first();
        $wilayahId = $wilayah ? $wilayah->id : null;

        // ── stats ──
        $stats = [
            'total' => BookingRequest::where('status', 'confirmed')
                        ->whereHas('bilik', function($q) use ($wilayahId) {
                            $q->where('wilayah_id', $wilayahId);
                        })->count(),
            'today' => BookingRequest::where('status', 'confirmed')
                        ->where('tarikh', $today->toDateString())
                        ->whereHas('bilik', function($q) use ($wilayahId) {
                            $q->where('wilayah_id', $wilayahId);
                        })->count(),
            'pending_users' => BookingUser::where('status', 'pending')
                                ->where('wilayah_id', $wilayahId)
                                ->count(),
            'total_users'   => BookingUser::where('status', 'approved')
                                ->where('wilayah_id', $wilayahId)
                                ->count(),
        ];

        // ── weekly chart (Mon–Sun of current week) ──
        $weekStart = $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $weeklyData = collect(range(0, 6))->map(function($i) use ($weekStart, $wilayahId) {
            $day = $weekStart->copy()->addDays($i);
            return [
                'label' => $day->translatedFormat('D, d M'),
                'count' => BookingRequest::where('status', 'confirmed')
                            ->where('tarikh', $day->toDateString())
                            ->whereHas('bilik', function($q) use ($wilayahId) {
                                $q->where('wilayah_id', $wilayahId);
                            })
                            ->count(),
            ];
        });

        // ── room availability today ──
        $allBilik = \App\Models\BookingBilik::where('wilayah_id', $wilayahId)
            ->orderBy('aras')
            ->orderBy('nama_bilik')
            ->get();
        $totalSlotMins = 9 * 60; // 8am-5pm

        $todayBookingsAll = BookingRequest::with('bilik')
            ->where('status', 'confirmed')
            ->where('tarikh', $today->toDateString())
            ->whereHas('bilik', function($q) use ($wilayahId) {
                $q->where('wilayah_id', $wilayahId);
            })
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
            ->whereHas('bilik', function($q) use ($wilayahId) {
                $q->where('wilayah_id', $wilayahId);
            })
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

    public function activityLog(Request $request)
    {
        $query = BookingActivityLog::query();
        $selectedActivity = $request->get('activity');

        if ($request->filled('activity')) {
            $query->where('action', $selectedActivity);
        }

        $activityLogs = $query->latest()->paginate(20)->withQueryString();

        $activityLabels = [
            'registered' => 'Pendaftaran Pengguna',
            'created_booking' => 'Tempahan Dibuat',
            'cancelled_booking' => 'Tempahan Dibatalkan',
            'updated_profile' => 'Profil Dikemaskini',
            'updated_password' => 'Kata Laluan Dikemaskini',
            'cross_region_booking' => 'Tempahan Luar Wilayah',
            'updated_user_status' => 'Status Pengguna Dikemaskini',
            'updated_booking_permission' => 'Kebenaran Tempahan Dikemaskini',
            'edited_user' => 'Pengguna Dikemaskini',
            'deleted_user' => 'Pengguna Dipadam',
            'added_user' => 'Pengguna Ditambah',
        ];

        $activityTypes = BookingActivityLog::select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('booking.admin.activity-log', compact(
            'activityLogs', 'activityLabels', 'activityTypes', 'selectedActivity'
        ));
    }

    public function editUser(Request $request, $id)
    {
        $request->validate([
        'name'       => 'required|string|max:255',
        'email'      => 'required|email|unique:booking_users,email,' . $id,
        'bahagian'   => 'nullable|string|max:255',
        'jawatan'    => 'nullable|string|max:255',
        'phone'      => 'nullable|string|max:20',
        'wilayah_id' => 'nullable|exists:wilayahs,id',
    ]);

    BookingUser::findOrFail($id)->update([
        'name'       => $request->name,
        'email'      => $request->email,
        'bahagian'   => $request->bahagian,
        'jawatan'    => $request->jawatan,
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
            'jawatan'    => 'nullable|string|max:255',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'wilayah_id' => 'nullable|exists:wilayahs,id',
        ]);

        $signaturePath = $request->file('signature')->store('signatures', 'public');

        BookingUser::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'bahagian'   => $request->bahagian,
            'jawatan'    => $request->jawatan,
            'signature'  => $signaturePath,
            'status'     => 'approved',
            'wilayah_id' => $request->wilayah_id,
            'can_book'   => false,
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        $adminName = Auth::guard('web')->user()->name;
        \App\Models\BookingActivityLog::log(
            'admin', $adminName,
            'added_user',
            'Admin ' . $adminName . ' menambah pengguna baharu ' . $request->name
        );

        return back()->with('success', 'Pengguna berjaya ditambah.');
    }

    public function users(Request $request)
    {
        // Users who have requested access but don't have it yet
        $applicants = BookingUser::with('wilayah')
            ->where('status', 'pending')
            ->where('can_book', false)
            ->latest()
            ->get();

        // Users who currently can book
        $bookableUsers = BookingUser::with('wilayah')
            ->where('can_book', true)
            ->latest()
            ->get();

        return view('booking.admin.users', compact('applicants', 'bookableUsers'));
    }

    // AJAX search for existing users (used by the "add by email" widget)
    public function searchUsers(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2']);

        $users = BookingUser::where('can_book', false)
            ->where('email', 'like', '%' . $request->q . '%')
            ->orderBy('email')
            ->limit(8)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    // Grant can_book — used both for approving a pending applicant and for the search-add widget
    public function grantCanBook(Request $request, $id)
    {
        $targetUser = BookingUser::findOrFail($id);
        $targetUser->update([
            'can_book' => true,
            'status'   => 'approved',
        ]);

        $adminName = Auth::guard('web')->user()->name;
        BookingActivityLog::log(
            'admin', $adminName,
            'updated_booking_permission',
            'Admin ' . $adminName . ' membenarkan tempahan untuk pengguna ' . $targetUser->name
        );

        return back()->with('success', 'Kebenaran tempahan berjaya diberikan kepada ' . $targetUser->name . '.');
    }

    // Withdraw can_book from a user who currently has it
    public function withdrawCanBook(Request $request, $id)
    {
        $targetUser = BookingUser::findOrFail($id);
        $targetUser->update(['can_book' => false]);

        $adminName = Auth::guard('web')->user()->name;
        BookingActivityLog::log(
            'admin', $adminName,
            'updated_booking_permission',
            'Admin ' . $adminName . ' menarik balik kebenaran tempahan pengguna ' . $targetUser->name
        );

        return back()->with('success', 'Kebenaran tempahan ' . $targetUser->name . ' berjaya ditarik balik.');
    }

public function resetPassword($id)
{
    // 1. Fetch user model profile
    $targetUser = \App\Models\BookingUser::findOrFail($id);
    
    // 2. Override with standard password string
    $targetUser->update([
        'password' => \Illuminate\Support\Facades\Hash::make('123456789')
    ]);

    // 3. Log administrative audit trace 
    $adminName = \Illuminate\Support\Facades\Auth::guard('web')->user()->name;
    \App\Models\BookingActivityLog::log(
        'admin', 
        $adminName,
        'edited_user', 
        'Admin ' . $adminName . ' mengeset semula kata laluan pengguna ' . $targetUser->name
    );

    // 4. Return safety bounce right back to your dashboard table view
    return redirect('/booking/admin/users')->with('success', 'Kata laluan berjaya diset semula kepada 123456789.');
}

}
