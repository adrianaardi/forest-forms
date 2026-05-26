<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Mail\BrevoMailer;
use App\Models\BookingBilik;
use App\Models\BookingRequest;
use App\Models\BookingUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bilik = BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get()->groupBy('aras');
        return view('booking.home', compact('bilik'));
    }

    public function calendar(Request $request)
    {
        $wilayahOrder = [
            'Ibu Pejabat', 'Kuching', 'Sri Aman', 'Sarikei',
            'Sibu', 'Kapit', 'Bintulu', 'Miri', 'Limbang', 'Lawas',
        ];

        $bilikList = \App\Models\BookingBilik::with('wilayah')
            ->whereHas('wilayah')
            ->get()
            ->groupBy(fn($b) => $b->wilayah->nama_wilayah)
            ->sortBy(fn($rooms, $wilayah) => array_search($wilayah, $wilayahOrder));
                $selectedId = $request->get('bilik') ?? \App\Models\BookingBilik::orderBy('aras')->orderBy('nama_bilik')->first()?->id;
                $bilik      = \App\Models\BookingBilik::find($selectedId);

        $weekStart = $request->get('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $bookings = collect();
        if ($bilik) {
            $bookings = BookingRequest::with('user')
                ->where('bilik_id', $bilik->id)
                ->whereIn('status', ['confirmed'])
                ->whereBetween('tarikh', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->get();
        }

        return view('booking.calendar', compact('bilikList', 'bilik', 'bookings', 'weekStart', 'weekEnd'));
    }
    public function showBook(Request $request, $bilikId = null)
    {
        $bilikList = BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get();
        $bilik     = $bilikId ? BookingBilik::find($bilikId) : null;
        $tarikh    = $request->get('tarikh', \Carbon\Carbon::today()->toDateString());
        $user      = Auth::guard('booking_user')->user();

        if (!$user) return redirect('/booking/login');

        return view('booking.book', compact('bilikList', 'bilik', 'tarikh', 'user'));
    }

    public function storeBook(Request $request, $bilikId = null)
    {
        $bilikId = $bilikId ?? $request->bilik_id;
        $bilik   = BookingBilik::findOrFail($bilikId);
        $user    = Auth::guard('booking_user')->user();

        $isAjax = $request->ajax() || $request->wantsJson();

        if (!$user) {
            if ($isAjax) {
                return response()->json(['message' => 'Sila log masuk untuk membuat tempahan.'], 401);
            }
            return redirect('/booking/login')->with('error', 'Sila log masuk untuk membuat tempahan.');
        }

        $validated = $request->validate([
            'bilik_id'        => 'required|exists:booking_bilik,id',
            'tajuk_mesyuarat' => 'required|string|max:255',
            'tarikh'          => 'required|date|after_or_equal:today',
            'masa_mula'       => 'required',
            'masa_tamat'      => 'required|after:masa_mula',
            'remarks'         => 'nullable|string|max:500',
        ]);
        // Note: Laravel automatically returns a 422 JSON response for AJAX
        // requests when validation fails, so no extra handling needed here.

        $mula  = \Carbon\Carbon::parse($request->masa_mula);
        $tamat = \Carbon\Carbon::parse($request->masa_tamat);

        if ($mula->hour < 8 || $tamat->hour > 17 || ($tamat->hour == 17 && $tamat->minute > 0)) {
            $msg = 'Masa tempahan mestilah dalam lingkungan 8:00 pagi hingga 5:00 petang.';
            if ($isAjax) {
                return response()->json(['message' => $msg], 422);
            }
            return back()->with('error', $msg)->withInput();
        }

        $conflict = BookingRequest::where('bilik_id', $bilikId)
            ->where('tarikh', $request->tarikh)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($request) {
                $q->where('masa_mula', '<', $request->masa_tamat)
                  ->where('masa_tamat', '>', $request->masa_mula);
            })->exists();

        if ($conflict) {
            $msg = 'Masa yang dipilih telah ditempah. Sila pilih masa lain.';
            if ($isAjax) {
                return response()->json(['message' => $msg], 409);
            }
            return back()->with('error', $msg)->withInput();
        }

        $token = \Illuminate\Support\Str::random(40);

        $booking = BookingRequest::create([
            'user_id'         => $user->id,
            'bilik_id'        => $bilikId,
            'tajuk_mesyuarat' => $request->tajuk_mesyuarat,
            'tarikh'          => $request->tarikh,
            'masa_mula'       => $request->masa_mula,
            'masa_tamat'      => $request->masa_tamat,
            'remarks'         => $request->remarks,
            'status'          => 'confirmed',
            'cancel_token'    => $token,
        ]);

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'created_booking',
            $user->name . ' membuat tempahan untuk ' . $bilik->nama_bilik . ' pada ' . \Carbon\Carbon::parse($request->tarikh)->format('d/m/Y') . ' (' . substr($request->masa_mula,0,5) . '–' . substr($request->masa_tamat,0,5) . ')'
        );

        $cancelUrl = url('/booking/cancel/' . $token);
        \App\Mail\BrevoMailer::send(
            $user->email,
            $user->name,
            'Pengesahan Tempahan — ' . $bilik->nama_bilik,
            view('emails.booking-confirmation', compact('booking', 'bilik', 'user', 'cancelUrl'))->render()
        );

        $redirectUrl = '/booking/calendar?bilik=' . $bilikId . '&week=' . $request->tarikh;
        $successMsg  = 'Tempahan berjaya! Emel pengesahan telah dihantar ke ' . $user->email;

        if ($isAjax) {
            return response()->json([
                'success'  => true,
                'message'  => $successMsg,
                'redirect' => $redirectUrl,
            ]);
        }

        return redirect($redirectUrl)->with('success', $successMsg);
    }


    public function cancelBooking($token)
    {
        $booking = BookingRequest::with(['user', 'bilik'])->where('cancel_token', $token)->firstOrFail();

        if ($booking->status === 'cancelled') {
            return redirect('/booking/calendar?bilik=' . $booking->bilik_id)
                ->with('info', 'Tempahan ini telah pun dibatalkan sebelum ini.');
        }

        $booking->update(['status' => 'cancelled']);

        \App\Models\BookingActivityLog::log(
            'user', $booking->user->name,
            'cancelled_booking',
            $booking->user->name . ' membatalkan tempahan untuk ' . $booking->bilik->nama_bilik . ' pada ' . \Carbon\Carbon::parse($booking->tarikh)->format('d/m/Y')
        );

        return redirect('/booking/calendar?bilik=' . $booking->bilik_id)
            ->with('success', 'Tempahan berjaya dibatalkan.');
    }

    public function myBookings()
    {
        $user = Auth::guard('booking_user')->user();
        if (!$user) return redirect('/booking/calendar');

        $upcoming = BookingRequest::with('bilik')
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->where('tarikh', '>=', \Carbon\Carbon::today()->toDateString())
            ->orderBy('tarikh')
            ->orderBy('masa_mula')
            ->get();

        $past = BookingRequest::with('bilik')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'cancelled'])
            ->where('tarikh', '<', \Carbon\Carbon::today()->toDateString())
            ->orderByDesc('tarikh')
            ->paginate(10);

        return view('booking.my-bookings', compact('upcoming', 'past'));
    }
}