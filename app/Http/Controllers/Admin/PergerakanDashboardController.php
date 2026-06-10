<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bahagian;
use App\Models\Pegawai;
use App\Models\Aktiviti;

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
}