<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuResetController extends Controller
{
    public function resetAll()
    {
        // Reset ALL Menu Foods & Drinks to 50
        \App\Models\Menu::whereIn('category_id', [1, 2])->update([
            'stock' => 50,
            'is_available' => true
        ]);

        return redirect()->back()->with('success', 'Semua stok menu (Makanan & Minuman) berhasil diisi ulang menjadi 50!');
    }}
