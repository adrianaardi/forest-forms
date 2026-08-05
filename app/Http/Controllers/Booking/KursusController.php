<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingKursus;
use App\Models\BookingKursusApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KursusController extends Controller
{
    public function index(Request $request): View
    {
        $scope = $request->string('scope')->toString();
        $search = trim($request->string('q')->toString());
        $bookingUser = Auth::guard('booking_user')->user();

        $query = BookingKursus::query()
            ->with('creator')
            ->withCount('applications')
            ->orderBy('tarikh_mula')
            ->orderByDesc('created_at');

        if ($scope === 'dalam') {
            $query->where('is_dalam_sarawak', true);
        } elseif ($scope === 'luar') {
            $query->where('is_dalam_sarawak', false);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('tajuk', 'like', '%' . $search . '%')
                    ->orWhere('penganjur', 'like', '%' . $search . '%')
                    ->orWhere('lokasi', 'like', '%' . $search . '%');
            });
        }

        $kursusList = $query->get();
        $appliedCourseIds = collect();

        if ($bookingUser) {
            $appliedCourseIds = BookingKursusApplication::where('booking_user_id', $bookingUser->id)
                ->pluck('kursus_id');
        }

        $stats = [
            'total' => BookingKursus::count(),
            'dalam' => BookingKursus::where('is_dalam_sarawak', true)->count(),
            'luar' => BookingKursus::where('is_dalam_sarawak', false)->count(),
        ];

        return view('booking.kursus.index', [
            'kursusList' => $kursusList,
            'scope' => $scope,
            'search' => $search,
            'stats' => $stats,
            'bookingUser' => $bookingUser,
            'appliedCourseIds' => $appliedCourseIds,
        ]);
    }

    public function create(): View
    {
        $bookingUser = Auth::guard('booking_user')->user();
        abort_unless($bookingUser && $bookingUser->can_book, 403);

        return view('booking.kursus.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $bookingUser = Auth::guard('booking_user')->user();
        abort_unless($bookingUser && $bookingUser->can_book, 403);

        $validated = $request->validate([
            'tajuk' => 'required|string|max:255',
            'penganjur' => 'required|string|max:255',
            'ringkasan' => 'required|string|max:1200',
            'lokasi' => 'required|string|max:255',
            'tarikh_mula' => 'required|date',
            'tarikh_tamat' => 'required|date|after_or_equal:tarikh_mula',
            'jumlah_tempat' => 'required|integer|min:1|max:999',
            'yuran' => 'nullable|numeric|min:0|max:999999.99',
            'is_dalam_sarawak' => 'required|boolean',
        ]);

        $validated['created_by'] = $bookingUser->id;

        BookingKursus::create($validated);

        return redirect()->route('booking.kursus.index')
            ->with('success', 'Kursus baharu berjaya ditambah ke dalam katalog.');
    }

    public function apply(BookingKursus $kursus): RedirectResponse
    {
        $bookingUser = Auth::guard('booking_user')->user();

        $exists = BookingKursusApplication::where('kursus_id', $kursus->id)
            ->where('booking_user_id', $bookingUser->id)
            ->exists();

        if ($exists) {
            return redirect()->route('booking.kursus.index')
                ->with('info', 'Anda telah menghantar permohonan untuk kursus ini sebelum ini.');
        }

        BookingKursusApplication::create([
            'kursus_id' => $kursus->id,
            'booking_user_id' => $bookingUser->id,
            'status' => 'pending',
        ]);

        return redirect()->route('booking.kursus.index')
            ->with('success', 'Permohonan kursus anda telah dihantar.');
    }
}