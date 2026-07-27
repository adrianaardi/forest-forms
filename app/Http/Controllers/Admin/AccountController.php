<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function update(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($account->id)],
            'wilayah_id' => 'required|exists:wilayahs,id',
            'password'   => 'nullable|min:8|confirmed',
        ]);

        $account->name = $validated['name'];
        $account->email = $validated['email'];
        $account->wilayah_id = $validated['wilayah_id'];

        if (!empty($validated['password'])) {
            $account->password = Hash::make($validated['password']);
        }

        $account->save();

        return back()->with('success', 'Akaun berjaya dikemaskini.');
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