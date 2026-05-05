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
        $bilikList   = BookingBilik::orderBy('aras')->orderBy('nama_bilik')->get()->groupBy('aras');
        $selectedId  = $request->get('bilik', BookingBilik::first()->id ?? null);
        $bilik       = BookingBilik::find($selectedId);

        // week navigation
        $weekStart = $request->get('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $bookings = collect();
        if ($bilik) {
            $bookings = BookingRequest::with('user')
                ->where('bilik_id', $bilik->id)
                ->where('status', 'confirmed')
                ->whereBetween('tarikh', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->get();
        }

        return view('booking.calendar', compact('bilikList', 'bilik', 'bookings', 'weekStart', 'weekEnd'));
    }

    public function showBook(Request $request, $bilikId)
    {
        $bilik  = BookingBilik::findOrFail($bilikId);
        $tarikh = $request->get('tarikh', Carbon::today()->toDateString());
        $user   = Auth::guard('booking_user')->user();

        return view('booking.book', compact('bilik', 'tarikh', 'user'));
    }

    public function storeBook(Request $request, $bilikId)
    {
        $bilik = BookingBilik::findOrFail($bilikId);

        $request->validate([
            'tajuk_mesyuarat' => 'required|string|max:255',
            'tarikh'          => 'required|date|after_or_equal:today',
            'masa_mula'       => 'required',
            'masa_tamat'      => 'required|after:masa_mula',
            'email'           => 'required|email',
            'password'        => 'required',
        ]);

        // verify user
        $user = BookingUser::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Emel atau kata laluan tidak sah.')->withInput();
        }

        if ($user->status === 'pending') {
            return back()->with('error', 'Akaun anda masih menunggu kelulusan admin.')->withInput();
        }

        if ($user->status === 'rejected') {
            return back()->with('error', 'Akaun anda telah ditolak.')->withInput();
        }

        // validate time range 8am-5pm
        $mula  = Carbon::parse($request->masa_mula);
        $tamat = Carbon::parse($request->masa_tamat);

        if ($mula->hour < 8 || $tamat->hour > 17 || ($tamat->hour == 17 && $tamat->minute > 0)) {
            return back()->with('error', 'Masa tempahan mestilah dalam lingkungan 8:00 pagi hingga 5:00 petang.')->withInput();
        }

        // check conflict
        $conflict = BookingRequest::where('bilik_id', $bilikId)
            ->where('tarikh', $request->tarikh)
            ->where('status', 'confirmed')
            ->where(function ($q) use ($request) {
                $q->where('masa_mula', '<', $request->masa_tamat)
                  ->where('masa_tamat', '>', $request->masa_mula);
            })->exists();

        if ($conflict) {
            return back()->with('error', 'Masa yang dipilih telah ditempah. Sila pilih masa lain.')->withInput();
        }

        $token = Str::random(40);

        $booking = BookingRequest::create([
            'user_id'         => $user->id,
            'bilik_id'        => $bilikId,
            'tajuk_mesyuarat' => $request->tajuk_mesyuarat,
            'tarikh'          => $request->tarikh,
            'masa_mula'       => $request->masa_mula,
            'masa_tamat'      => $request->masa_tamat,
            'status'          => 'confirmed',
            'cancel_token'    => $token,
        ]);

        // send confirmation email
        $cancelUrl = url('/booking/cancel/' . $token);
        BrevoMailer::send(
            $user->email,
            $user->name,
            'Pengesahan Tempahan — ' . $bilik->nama_bilik,
            view('emails.booking-confirmation', compact('booking', 'bilik', 'user', 'cancelUrl'))->render()
        );

        return redirect('/booking/calendar?bilik=' . $bilikId . '&week=' . $request->tarikh)
            ->with('success', 'Tempahan berjaya! Emel pengesahan telah dihantar ke ' . $user->email);
    }

    public function cancelBooking($token)
    {
        $booking = BookingRequest::with(['user', 'bilik'])->where('cancel_token', $token)->firstOrFail();

        if ($booking->status === 'cancelled') {
            return view('booking.cancelled', ['alreadyCancelled' => true, 'booking' => $booking]);
        }

        $booking->update(['status' => 'cancelled']);

        return view('booking.cancelled', ['alreadyCancelled' => false, 'booking' => $booking]);
    }
}