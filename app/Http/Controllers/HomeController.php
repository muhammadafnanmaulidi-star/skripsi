<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Ambil categories dengan subcategories dan menus
            $categories = Category::with([
                'subcategories' => function ($query) {
                    $query->orderBy('order');
                },
                'subcategories.menus' => function ($query) {
                    $query->orderBy('is_available', 'desc')->orderBy('name');
                }
            ])->orderBy('order')->get();

            // Ambil banners aktif
            $banners = Banner::where('is_active', true)->latest()->get();

            // Format data untuk Vue
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
                                    'is_available' => (bool)$menu->is_available,
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ];
            })->toArray();

            return Inertia::render('Home/Home', [
                'categories' => $formatted,
                'banners' => $banners
            ]);
            
        } catch (\Exception $e) {
            \Log::error('HomeController@index error: ' . $e->getMessage());
            return Inertia::render('Home/Home', [
                'categories' => [],
                'banners' => []
            ]);
        }
    }
}