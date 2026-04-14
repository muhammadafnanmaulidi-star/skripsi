<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Inertia\Inertia;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->get();

        return Inertia::render('Promo/Promo', [
            'promos' => $promos
        ]);
    }
}