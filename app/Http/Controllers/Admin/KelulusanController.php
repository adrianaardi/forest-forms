<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelulusanController extends Controller
{
    private const ALLOWED_EMAIL = 'admin.kelulusanperjalanan@sarawak.gov.my';

    private function authorizeAdmin(): void
    {
        $user = Auth::user();
        if (!$user || $user->email !== self::ALLOWED_EMAIL) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $supervisors = BookingUser::where('is_supervisor', true)
            ->orderBy('name')
            ->get();

        $hods = BookingUser::where('is_hod', true)
            ->orderBy('name')
            ->get();

        $accountants = BookingUser::where('is_accountant', true)
            ->orderBy('name')
            ->get();

        return view('admin.kelulusan', compact('supervisors', 'hods', 'accountants'));
    }

    public function search(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'q' => 'required|string|min:2',
            'role' => 'required|in:supervisor,hod,accountant',
        ]);

        $roleColumn = match ($request->role) {
            'supervisor' => 'is_supervisor',
            'hod' => 'is_hod',
            'accountant' => 'is_accountant',
        };

        $users = BookingUser::query()
            ->where($roleColumn, false)
            ->where('email', 'like', '%' . $request->q . '%')
            ->orderBy('email')
            ->limit(8)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function assign(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'user_id' => 'required|exists:booking_users,id',
            'role' => 'required|in:supervisor,hod,accountant',
        ]);

        $user = BookingUser::findOrFail($request->user_id);

        $roleColumn = match ($request->role) {
            'supervisor' => 'is_supervisor',
            'hod' => 'is_hod',
            'accountant' => 'is_accountant',
        };

        $user->update([$roleColumn => true]);

        return back()->with('success', $user->email . ' berjaya ditambah ke senarai ' . strtoupper($request->role) . '.');
    }

    public function remove(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'user_id' => 'required|exists:booking_users,id',
            'role' => 'required|in:supervisor,hod,accountant',
        ]);

        $user = BookingUser::findOrFail($request->user_id);

        $roleColumn = match ($request->role) {
            'supervisor' => 'is_supervisor',
            'hod' => 'is_hod',
            'accountant' => 'is_accountant',
        };

        $user->update([$roleColumn => false]);

        return back()->with('success', $user->email . ' berjaya dibuang dari senarai ' . strtoupper($request->role) . '.');
    }
}
