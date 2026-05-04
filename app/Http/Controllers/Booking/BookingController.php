<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingBilik;
use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bilik = BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get()->groupBy('aras');
        return view('booking.home', compact('bilik'));
    }

    public function userDashboard()
    {
        $user = Auth::guard('booking_user')->user();
        $bookings = BookingRequest::with('bilik')
            ->where('user_id', $user->id)
            ->orderBy('tarikh', 'desc')
            ->get();
        return view('booking.user.dashboard', compact('bookings'));
    }

    public function calendar(Request $request, $bilikId)
    {
        $bilik = BookingBilik::findOrFail($bilikId);

        $month = $request->get('month', Carbon::now()->month);
        $year  = $request->get('year', Carbon::now()->year);

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $bookings = BookingRequest::with('user')
            ->where('bilik_id', $bilikId)
            ->where('status', 'approved')
            ->whereBetween('tarikh', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy('tarikh');

        return view('booking.calendar', compact('bilik', 'bookings', 'month', 'year', 'startOfMonth', 'endOfMonth'));
    }

    public function showTempah($bilikId)
    {
        $bilik = BookingBilik::findOrFail($bilikId);
        return view('booking.tempah', compact('bilik'));
    }

    public function storeTempah(Request $request, $bilikId)
    {
        $bilik = BookingBilik::findOrFail($bilikId);
        $user  = Auth::guard('booking_user')->user();

        $request->validate([
            'tarikh'    => 'required|date|after_or_equal:today',
            'masa_mula' => 'required',
            'masa_tamat' => 'required|after:masa_mula',
            'tujuan'    => 'required|string|max:500',
        ]);

        // check 8am-5pm
        $mula  = Carbon::parse($request->masa_mula);
        $tamat = Carbon::parse($request->masa_tamat);

        if ($mula->hour < 8 || $tamat->hour > 17 || ($tamat->hour == 17 && $tamat->minute > 0)) {
            return back()->with('error', 'Masa tempahan mestilah dalam lingkungan 8:00 pagi hingga 5:00 petang.')->withInput();
        }

        // check for conflicts
        $conflict = BookingRequest::where('bilik_id', $bilikId)
            ->where('tarikh', $request->tarikh)
            ->where('status', 'approved')
            ->where(function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('masa_mula', '<', $request->masa_tamat)
                       ->where('masa_tamat', '>', $request->masa_mula);
                });
            })->exists();

        if ($conflict) {
            return back()->with('error', 'Masa yang dipilih telah ditempah. Sila pilih masa lain.')->withInput();
        }

        BookingRequest::create([
            'user_id'   => $user->id,
            'bilik_id'  => $bilikId,
            'tarikh'    => $request->tarikh,
            'masa_mula' => $request->masa_mula,
            'masa_tamat' => $request->masa_tamat,
            'tujuan'    => $request->tujuan,
            'status'    => 'pending',
        ]);

        return redirect('/booking/user/dashboard')->with('success', 'Tempahan berjaya dihantar! Sila tunggu kelulusan admin.');
    }

    public function batal($id)
    {
        $user    = Auth::guard('booking_user')->user();
        $booking = BookingRequest::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if ($booking->status === 'approved' && Carbon::parse($booking->tarikh)->isPast()) {
            return back()->with('error', 'Tempahan yang telah berlalu tidak boleh dibatalkan.');
        }

        $booking->delete();
        return back()->with('success', 'Tempahan berjaya dibatalkan.');
    }
}