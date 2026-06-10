<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bahagian;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Aktiviti;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class BahagianController extends Controller
{
    public function storeBahagian(Request $request)
    {
        $request->validate(['nama' => 'required|unique:bahagians,nama|max:255']);
        
        Bahagian::create(['nama' => $request->nama]);
        
        return redirect()->back()->with('success', 'Bahagian successfully created!');
    }

    public function storeSubAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'bahagian_id' => 'required|exists:bahagians,id',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'subadmin_pergerakan',
            'bahagian_id' => $request->bahagian_id,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Sub-Admin registered and bound to Division successfully!');
    }
}