<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'headline' => 'required|string|max:255',
        ]);

        $bahagianId = Auth::user()->bahagian_id;
        $count = News::where('bahagian_id', $bahagianId)->count();

        if ($count >= 5) {
            return back()->with('error', 'Had maksimum 5 pengumuman telah dicapai. Padam slot sedia ada terlebih dahulu.');
        }

        News::create([
            'headline'    => $request->headline,
            'bahagian_id' => $bahagianId,
        ]);

        return back()->with('success', 'Pengumuman berjaya disiarkan.');
    }

    public function destroy($id)
    {
        $news = News::where('id', $id)
            ->where('bahagian_id', Auth::user()->bahagian_id)
            ->firstOrFail();

        $news->delete();

        return back()->with('success', 'Pengumuman berjaya dipadam.');
    }
}