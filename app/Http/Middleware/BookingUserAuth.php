<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingUserAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('booking_user')->check()) {
            return redirect('/booking/login')->with('error', 'Sila log masuk untuk meneruskan.');
        }

        if (Auth::guard('booking_user')->user()->status !== 'approved') {
            Auth::guard('booking_user')->logout();
            return redirect('/booking/login')->with('error', 'Akaun anda belum diluluskan oleh admin.');
        }

        return $next($request);
    }
}