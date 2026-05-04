<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingAdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('web')->check() || Auth::guard('web')->user()->email !== 'admin.booking@sarawak.gov.my') {
            return redirect('/booking/login')->with('error', 'Sila log masuk sebagai admin.');
        }
        return $next($request);
    }
}