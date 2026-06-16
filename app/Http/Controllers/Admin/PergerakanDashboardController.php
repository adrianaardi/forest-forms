<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bahagian;
use App\Models\Pegawai;
use App\Models\Aktiviti;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;

class PergerakanDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.pergerakan.main-dashboard', [
                'bahagianList' => Bahagian::all(),
                'subadmins'    => User::where('role', 'subadmin_pergerakan')
                                      ->with('bahagian')
                                      ->get(),
            ]);
        }

        // Subadmin branch
        $bahagianId = $user->bahagian_id;
        $mingguMula  = Carbon::now()->startOfWeek()->toDateString();
        $mingguTamat = Carbon::now()->endOfWeek()->toDateString();

        return view('admin.pergerakan.subadmin-dashboard', [
            'pegawaiList' => Pegawai::where('bahagian_id', $bahagianId)->get(),
            'aktivitiList' => Aktiviti::where('bahagian_id', $bahagianId)
                ->where('tarikh', '>=', now()->subDays(7)->toDateString())
                ->orderBy('tarikh')
                ->get(),
            'newsList'    => News::where('bahagian_id', $bahagianId)
                                  ->latest()
                                  ->get(),
            'mingguMula'  => $mingguMula,
            'mingguTamat' => $mingguTamat,
        ]);
    }

    public function updateBahagian(Request $request, $id)
    {
        Bahagian::findOrFail($id)->update(['nama' => $request->nama]);
        return back()->with('success', 'Bahagian berjaya dikemaskini.');
    }

    public function destroyBahagian($id)
    {
        Bahagian::findOrFail($id)->delete();
        return back()->with('success', 'Bahagian berjaya dipadam.');
    }

    public function updateSubAdmin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->bahagian_id = $request->bahagian_id;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        return back()->with('success', 'Akaun Sub-Admin berjaya dikemaskini.');
    }

    public function destroySubAdmin($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akaun Sub-Admin berjaya dipadam.');
    }
}