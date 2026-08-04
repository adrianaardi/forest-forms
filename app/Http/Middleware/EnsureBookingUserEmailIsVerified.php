<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureBookingUserEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        $guard = Auth::guard('booking_user');
        $isAjax = $request->ajax() || $request->wantsJson();

        if (!$guard->check()) {
            if ($isAjax) {
                return response()->json([
                    'message' => 'Sila log masuk terlebih dahulu.',
                ], 401);
            }

            return redirect('/booking/login')->with('error', 'Sila log masuk terlebih dahulu.');
        }

        $user = $guard->user();

        if (!$user->email_verified_at) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($isAjax) {
                return response()->json([
                    'message' => 'Sila sahkan emel anda terlebih dahulu melalui pautan yang dihantar.',
                    'needs_verification' => true,
                ], 422);
            }

            return redirect('/booking/calendar')->with('error', 'Sila sahkan emel anda terlebih dahulu melalui pautan yang dihantar.');
        }

        return $next($request);
    }
}