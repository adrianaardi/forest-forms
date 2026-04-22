<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BahagianSupervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BahagianController extends Controller
{
    public function index()
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);
        $bahagian = BahagianSupervisor::latest()->get();
        return view('admin.bahagian', compact('bahagian'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);

        $request->validate([
            'nama_bahagian'    => 'required|string|max:255|unique:bahagian_supervisors,nama_bahagian',
            'email_supervisor' => 'required|email',
        ]);

        BahagianSupervisor::create($request->only('nama_bahagian', 'email_supervisor'));

        return back()->with('success', 'Bahagian berjaya ditambah.');
    }

    public function destroy($id)
    {
        if (Auth::user()->email !== 'admin.mohon@sarawak.gov.my') abort(403);
        BahagianSupervisor::findOrFail($id)->delete();
        return back()->with('success', 'Bahagian berjaya dipadam.');
    }
}