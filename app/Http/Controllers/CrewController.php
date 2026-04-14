<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CrewController extends Controller
{
    public function index()
    {
        $crews = Crew::ordered()->get();
        
        // Add full URL
        $crews->transform(function ($crew) {
            $crew->image_url = asset('storage/' . $crew->image);
            return $crew;
        });

        return Inertia::render('Admin/AdminCrew', [
            'crews' => $crews
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'name' => ['required', 'string', 'max:50', 'regex:/^[^0-9]*$/'],
            'position' => ['required', 'string', 'max:255'],
        ]);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crews', 'public');
            $validated['image'] = $path;
        }

        $crew = Crew::create($validated);
        $crew->image_url = asset('storage/' . $crew->image);

        return response()->json([
            'success' => true,
            'crew' => $crew
        ]);
    }

    public function update(Request $request, $id)
    {
        $crew = Crew::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'regex:/^[^0-9]*$/'],
            'position' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);


        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($crew->image && Storage::disk('public')->exists($crew->image)) {
                Storage::disk('public')->delete($crew->image);
            }
            
            $path = $request->file('image')->store('crews', 'public');
            $validated['image'] = $path;
        }

        $crew->update($validated);
        
        $crew->image_url = asset('storage/' . $crew->image);

        return response()->json([
            'success' => true,
            'crew' => $crew
        ]);
    }

    public function destroy($id)
    {
        $crew = Crew::findOrFail($id);

        if (Storage::disk('public')->exists($crew->image)) {
            Storage::disk('public')->delete($crew->image);
        }

        $crew->delete();

        return response()->json(['success' => true]);
    }

    public function getActive()
    {
        $crews = Crew::active()->ordered()->get();

        $crews->transform(function ($crew) {
            $crew->image_url = asset('storage/' . $crew->image);
            return $crew;
        });

        return response()->json($crews);
    }
}
