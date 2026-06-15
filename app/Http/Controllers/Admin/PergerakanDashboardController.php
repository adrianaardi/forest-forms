<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bahagian;
use App\Models\Pegawai;
use App\Models\Aktiviti;
use App\Models\User;

class PergerakanDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // super admin / admin
        if ($user->role === 'admin') {
            $bahagianList = Bahagian::all();
            $subadmins = \App\Models\User::where('role', 'subadmin_pergerakan')
                ->with('bahagian')
                ->get();

            return view('admin.pergerakan.main-dashboard', compact(
                'bahagianList',
                'subadmins'
            ));
        }

        // subadmin pergerakan
        $bahagianId = $user->bahagian_id;

        $pegawaiList = Pegawai::where('bahagian_id', $bahagianId)->get();
        $aktivitiList = Aktiviti::where('bahagian_id', $bahagianId)->get();

        return view('admin.pergerakan.subadmin-dashboard', compact(
            'pegawaiList',
            'aktivitiList'
        ));
    }

    public function updateBahagian(Request $request, $id)
    {
        $bahagian = Bahagian::findOrFail($id);
        $bahagian->update(['nama' => $request->nama]);
        return back()->with('success', 'Bahagian berjaya dikemaskini.');
    }

    public function destroyBahagian($id)
    {
        $bahagian = Bahagian::findOrFail($id);
        $bahagian->delete();
        return back()->with('success', 'Bahagian berjaya dipadam.');
    }

    public function updateSubAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->bahagian_id = $request->bahagian_id;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return back()->with('success', 'Akaun Sub-Admin berjaya dikemaskini.');
    }

    public function destroySubAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Akaun Sub-Admin berjaya dipadam.');
    }
}