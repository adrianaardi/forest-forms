<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::latest()->get();
        $wilayahs = \App\Models\Wilayah::all();
        return view('admin.accounts', compact('accounts','wilayahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'wilayah_id' => 'required|exists:wilayahs,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'sub_admin',
            'wilayah_id' => $request->wilayah_id,
        ]);

        return back()->with('success', 'Akaun berjaya ditambah.');
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return back()->withErrors(['delete' => 'Anda tidak boleh memadam akaun anda sendiri.']);
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'Akaun berjaya dipadam.');
    }
}