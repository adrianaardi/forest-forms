<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookingUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // <-- was missing

class BookingUserProfileController extends Controller
{
    private function guard()
    {
        return Auth::guard('booking_user');
    }

    public function index()
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }
        $user = $this->guard()->user()->load(['supervisor', 'supervisees']);
        $wilayahs = \App\Models\Wilayah::orderBy('nama_wilayah')->get();
        return view('booking.user.profile', compact('user', 'wilayahs'));
    }

    public function searchSupervisors(Request $request)
    {
        if (!$this->guard()->check()) {
            return response()->json([], 401);
        }

        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $currentUser = $this->guard()->user();

        $users = BookingUser::query()
            ->where('id', '!=', $currentUser->id)
            ->where('email', 'like', '%' . $request->q . '%')
            ->orderBy('email')
            ->limit(8)
            ->get(['id', 'name', 'email', 'bahagian', 'jawatan']);

        return response()->json($users);
    }

    public function assignSupervisor(Request $request)
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $request->validate([
            'supervisor_id' => 'required|exists:booking_users,id',
        ]);

        $user = $this->guard()->user();
        $supervisor = BookingUser::find($request->supervisor_id);

        if (!$supervisor) {
            return back()->withErrors(['supervisor' => 'Pengguna dipilih tidak dijumpai.']);
        }

        if ((int) $supervisor->id === (int) $user->id) {
            return back()->withErrors(['supervisor' => 'Anda tidak boleh menetapkan diri sendiri sebagai supervisor.']);
        }

        $supervisor->is_supervisor = true;
        $supervisor->save();

        $user->supervisor_id = $supervisor->id;
        $user->save();

        \App\Models\BookingActivityLog::log(
            'user',
            $user->name,
            'assigned_supervisor',
            $user->name . ' menetapkan supervisor: ' . $supervisor->email
        );

        return back()->with('success', 'Supervisor berjaya ditetapkan: ' . $supervisor->email);
    }

    public function removeSupervisor()
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $user = $this->guard()->user();

        if ($user->supervisor_id) {
            $oldSupervisor = $user->supervisor;
            $user->supervisor_id = null;
            $user->save();

            \App\Models\BookingActivityLog::log(
                'user',
                $user->name,
                'removed_supervisor',
                $user->name . ' membuang supervisor: ' . ($oldSupervisor->email ?? '-')
            );
        }

        return back()->with('success', 'Supervisor berjaya dibuang.');
    }

    public function update(Request $request)
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $user = $this->guard()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:booking_users,email,' . $user->id,
            'bahagian'   => 'nullable|string|max:255',
            'jawatan'    => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'signature'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'wilayah_id' => 'required|exists:wilayahs,id',
        ]);

        if ($request->hasFile('signature')) {
            // delete old signature file
            if ($user->signature) {
                Storage::disk('public')->delete($user->signature);
            }

            $user->signature = $request->file('signature')->store('signatures', 'public');
        }

        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->bahagian   = $request->bahagian;
        $user->jawatan    = $request->jawatan;
        $user->phone      = $request->phone;
        $user->wilayah_id = $request->wilayah_id;
        $user->save();

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'updated_profile',
            $user->name . ' mengemaskini maklumat profile'
        );

        return back()->with('success', 'Profil berjaya dikemaskini.');
    }

    public function updatePassword(Request $request)
    {
        if (!$this->guard()->check()) {
            return redirect('/booking/login');
        }

        $user = $this->guard()->user();

        $request->validate([
            'current_password' => 'required',
            'password'          => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata laluan semasa tidak betul.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        \App\Models\BookingActivityLog::log(
            'user', $user->name,
            'updated_password',
            $user->name . ' menukar kata laluan'
        );

        return back()->with('success', 'Kata laluan berjaya dikemaskini.');
    }
}