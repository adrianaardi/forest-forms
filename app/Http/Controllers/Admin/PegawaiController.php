<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use App\Models\Aktiviti;
use App\Models\User;
use App\Models\Bahagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        // 1. Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Sila log masuk untuk mengakses aplikasi ini.');
        }

        $user = auth()->user();

        // 2. SCENARIO A: MAIN ADMIN WORKSPACE (Sections & Sub-Admins Only)
        if ($user->email === 'admin.pergerakan@sarawak.gov.my') {
            $subadmins = User::where('role', 'subadmin_pergerakan')->latest()->get();
            $seksyenList = Bahagian::latest()->get();

            return view('admin.pergerakan-pegawai.main-dashboard', compact('subadmins', 'seksyenList'));
        }

        // 3. SCENARIO B: SUB-ADMIN WORKSPACE (Officers, Attendance & Activities)
        if ($user->role === 'subadmin_pergerakan') {
            $pegawaiList = Pegawai::latest()->get();
            $seksyenList = Bahagian::latest()->get(); 
            $aktivitiList = Aktiviti::latest()->get(); // Activities shifted here

            return view('admin.pergerakan-pegawai.subadmin-dashboard', compact('pegawaiList', 'seksyenList', 'aktivitiList'));
        }

        abort(403, 'Anda tidak mempunyai kebenaran untuk mengakses modul ini.');
    }

    public function storePegawai(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gred' => 'required|string|max:50',
            'seksyen_unit' => 'required|string|max:100',
        ]);

        Pegawai::create([
            'nama' => $request->nama,
            'gred' => $request->gred,
            'seksyen_unit' => $request->seksyen_unit,
            'is_hadir' => true,
        ]);

        return redirect()->back()->with('success', 'Pegawai berjaya didaftarkan ke dalam roster sistem!');
    }

    public function storeBahagian(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:bahagians,nama',
        ]);

        \App\Models\Bahagian::create([
            'nama' => $request->nama
        ]);

        return redirect()->back()->with('success', 'Seksyen / Unit baru berjaya didaftarkan!');
    }

    public function toggleStatus($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->is_hadir = !$pegawai->is_hadir;
        $pegawai->save();

        return redirect()->back()->with('success', 'Status pergerakan kehadiran pegawai berjaya dikemaskini!');
    }

    public function storeSubAdmin(\Illuminate\Http\Request $request)
    {
        // 1. Validate the form inputs
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // 2. Create the Sub-Admin account
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password), // Encrypts the raw password securely
            'role'     => 'subadmin_pergerakan',       // Sets the correct access tier
        ]);

        // 3. Send them back with a clean success flash alert
        return redirect()->back()->with('success', 'Akaun Sub-Admin bagi ' . $request->name . ' berjaya didaftarkan!');
    }
}