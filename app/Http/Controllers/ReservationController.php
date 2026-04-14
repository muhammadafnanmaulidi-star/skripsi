<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        return Inertia::render('Reservation/Reservation');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|regex:/^[^0-9]*$/',
            'email' => 'required|email',
            'phone' => 'required|string|min:10|regex:/^[0-9]+$/',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'guests' => 'required|integer|min:1|max:20',
            'notes' => 'nullable|string'
        ]);

        Reservation::create($validated);

        return redirect()->back()->with('success', 'Reservation submitted successfully!');
    }
}