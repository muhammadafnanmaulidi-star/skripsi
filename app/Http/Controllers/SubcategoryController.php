<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SubcategoryController extends Controller
{
    /**
     * Tampilkan halaman SubCategory.vue dengan relasi subcategories
     */
    public function index()
    {
        return Inertia::render('Admin/Subcategory', [
            'categories' => Category::with('subcategories')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store subcategory baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255|unique:subcategories,name',
            'order'       => 'nullable|integer|min:0',
        ]);


        Subcategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'order'       => $request->order ?? 0,
        ]);

        return back()->with('success', 'Subcategory berhasil ditambahkan!');
    }

    /**
     * Update subcategory
     */
    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255|unique:subcategories,name,' . $id,
            'order' => 'nullable|integer|min:0',
        ]);


        $subcategory->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'order' => $request->order ?? 0,
        ]);


        return back()->with('success', 'Subcategory berhasil diperbarui!');
    }

    /**
     * Hapus subcategory
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->delete();

        return back()->with('success', 'Subcategory berhasil dihapus!');
    }
}
