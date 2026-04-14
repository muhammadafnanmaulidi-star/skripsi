<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BannerController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Banner/Index', [
            'banners' => Banner::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');

            Banner::create([
                'image' => '/storage/' . $path,
                'title' => $request->title,
                'is_active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan');
    }

    public function destroy(Banner $banner)
    {
        // Hapus file fisik
        $path = str_replace('/storage/', '', $banner->image);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Banner berhasil dihapus');
    }
}
