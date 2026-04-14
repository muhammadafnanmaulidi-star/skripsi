<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    // Tampilkan home dengan menu dari database
    public function index()
    {
        try {
            $categories = Category::with([
                'subcategories.menus' => function ($query) {
                    $query->where('is_available', true)->orderBy('name');
                }
            ])->orderBy('order')->get();

            $formatted = $categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'subcategories' => $cat->subcategories->map(function ($sub) {
                        return [
                            'id' => $sub->id,
                            'name' => $sub->name,
                            'menus' => $sub->menus->map(function ($menu) {
                                return [
                                    'id' => $menu->id,
                                    'name' => $menu->name,
                                    'price' => (float)$menu->price,
                                    'description' => $menu->description,
                                    'image' => $menu->image ? asset('storage/' . $menu->image) : null,
                                    'image_position' => $menu->image_position,
                                    'image_zoom' => (float)$menu->image_zoom,
                                    'discount_percent' => (int)$menu->discount_percent,
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ];
            })->toArray();

            return Inertia::render('Home', ['categories' => $formatted]);
        } catch (\Exception $e) {
            \Log::error('MenuController@index error: ' . $e->getMessage());
            return Inertia::render('Home', ['categories' => []]);
        }
    }

    // Tambah menu baru
    public function store(Request $request)
    {
        try {
            \Log::info('Store Menu Request:', [
                'request_data' => $request->except('image'),
                'has_image' => $request->hasFile('image')
            ]);

            $validated = $request->validate([
                'category_id'     => 'required|exists:categories,id',
                'subcategory_id'  => 'required|exists:subcategories,id',
                'name'            => 'required|string|max:255|unique:menus,name',
                'main_ingredient' => 'nullable|string|max:255',

                'price'           => 'required|numeric|min:0',
                'discount_percent'=> 'nullable|integer|min:0|max:100',
                'description'     => 'nullable|string',
                'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'image_position'  => 'nullable|string|max:255',
                'image_zoom'      => 'nullable|numeric|min:0.1|max:5',
                'special_type'    => 'nullable|string|in:weekly,monthly',
                'stock'           => 'required|integer|min:0',
            ]);

            // Validasi subcategory milik category
            $subcategoryExists = Subcategory::where('id', $validated['subcategory_id'])
                ->where('category_id', $validated['category_id'])
                ->exists();
            
            \Log::info('Subcategory validation:', [
                'subcategory_id' => $validated['subcategory_id'],
                'category_id' => $validated['category_id'],
                'exists' => $subcategoryExists
            ]);

            if (!$subcategoryExists) {
                \Log::warning('Subcategory tidak sesuai dengan Category', [
                    'subcategory_id' => $validated['subcategory_id'],
                    'category_id' => $validated['category_id']
                ]);
                return redirect()->back()->withErrors(['subcategory_id' => 'Subcategory tidak sesuai dengan Category'])->withInput();
            }

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('menu-images', 'public');
                \Log::info('Image uploaded:', ['path' => $imagePath]);
            }

            $menu = Menu::create([
                'category_id'     => $validated['category_id'],
                'subcategory_id'  => $validated['subcategory_id'],
                'name'            => $validated['name'],
                'main_ingredient' => $validated['main_ingredient'] ?? null,
                'price'           => $validated['price'],
                'discount_percent'=> $validated['discount_percent'] ?? 0,
                'description'     => $validated['description'] ?? null,
                'image'           => $imagePath,
                'image_position'  => $validated['image_position'] ?? 'center',
                'image_zoom'      => $validated['image_zoom'] ?? 1.0,
                'special_type'    => $validated['special_type'] ?? null,
                'stock'           => $validated['stock'] ?? 0,
                'is_available'    => true, // Tetap true karena tidak ada fitur non-aktif
            ]);

            \Log::info('Menu berhasil dibuat', ['menu_id' => $menu->id]);

            return redirect()->back()->with('success', 'Menu berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error on Menu Store:', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Store Menu Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal menambahkan menu: ' . $e->getMessage());
        }
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        try {
            \Log::info('Update Menu Request:', [
                'menu_id' => $menu->id,
                'request_data' => $request->except('image')
            ]);

            $validated = $request->validate([
                'category_id'     => 'required|exists:categories,id',
                'subcategory_id'  => 'required|exists:subcategories,id',
                'name'            => 'required|string|max:255|unique:menus,name,' . $menu->id,
                'main_ingredient' => 'nullable|string|max:255',

                'price'           => 'required|numeric|min:0',
                'discount_percent'=> 'nullable|integer|min:0|max:100',
                'description'     => 'nullable|string',
                'image'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
                'image_position'  => 'nullable|string|max:255',
                'image_zoom'      => 'nullable|numeric|min:0.1|max:5',
                'special_type'    => 'nullable|string|in:weekly,monthly',
                'stock'           => 'required|integer|min:0',
            ]);

            // Validasi subcategory milik category
            if (!Subcategory::where('id', $validated['subcategory_id'])
                ->where('category_id', $validated['category_id'])->exists()) {
                \Log::warning('Subcategory tidak sesuai dengan Category', [
                    'subcategory_id' => $validated['subcategory_id'],
                    'category_id' => $validated['category_id']
                ]);
                return redirect()->back()->withErrors(['subcategory_id' => 'Subcategory tidak sesuai dengan Category'])->withInput();
            }

            $data = [
                'category_id'     => $validated['category_id'],
                'subcategory_id'  => $validated['subcategory_id'],
                'name'            => $validated['name'],
                'main_ingredient' => $validated['main_ingredient'] ?? null,
                'price'           => $validated['price'],
                'discount_percent'=> $validated['discount_percent'] ?? 0,
                'description'     => $validated['description'] ?? null,
                'image_position'  => $validated['image_position'] ?? 'center',
                'image_zoom'      => $validated['image_zoom'] ?? 1.0,
                'special_type'    => $validated['special_type'] ?? null,
                'stock'           => $validated['stock'] ?? 0,
                'is_available'    => true, // Tetap true
            ];

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($menu->image) {
                    \Storage::disk('public')->delete($menu->image);
                }
                $data['image'] = $request->file('image')->store('menu-images', 'public');
            }

            $menu->update($data);

            \Log::info('Menu berhasil diupdate', ['menu_id' => $menu->id]);

            return redirect()->back()->with('success', 'Menu berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation Error on Menu Update:', [
                'menu_id' => $menu->id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Update Menu Error:', [
                'menu_id' => $menu->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal memperbarui menu: ' . $e->getMessage());
        }
    }

    // Hapus menu
    public function destroy(Menu $menu)
    {
        try {
            if ($menu->image) {
                \Storage::disk('public')->delete($menu->image);
            }

            $menu->delete();

            return redirect()->back()->with('success', 'Menu berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus menu');
        }
    }

    // Toggle ketersediaan menu (Tersedia / Habis)
    public function toggleAvailability(Menu $menu)
    {
        $newStock = $menu->stock > 0 ? 0 : 50;
        
        $menu->update([
            'stock' => $newStock,
            'is_available' => true // Fitur non-aktif dihapus
        ]);
    
        $status = $newStock > 0 ? 'stok terisi 50' : 'stok habis';
        return redirect()->back()->with('success', "Stok menu '{$menu->name}' sekarang {$status}.");
    }
   public function adminMenuPage()
{
    $foodsCategoryId = 1;
    $drinksCategoryId = 2;

    $foodsMenus = Menu::where('category_id', $foodsCategoryId)
        ->where('is_available', true)
        ->get();

    $drinksMenus = Menu::where('category_id', $drinksCategoryId)
        ->where('is_available', true)
        ->get();

    // 🔥 Tambahkan ini
    $foodsSubcategories = Subcategory::where('category_id', $foodsCategoryId)
        ->orderBy('order')
        ->get();

    $drinksSubcategories = Subcategory::where('category_id', $drinksCategoryId)
        ->orderBy('order')
        ->get();

    $totalSubcategories = $foodsSubcategories->count() + $drinksSubcategories->count();

    return Inertia::render('HomeAdmin', [
        'foodsMenus' => $foodsMenus,
        'drinksMenus' => $drinksMenus,
        'foodsSubcategories' => $foodsSubcategories,
        'drinksSubcategories' => $drinksSubcategories,
        'totalSubcategories' => $totalSubcategories, // kirim ke frontend
    ]);
}


}
