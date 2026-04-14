<?php

namespace App\Http\Controllers;

use App\Models\CommunityPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CommunityPhotoController extends Controller
{
    /**
     * GET /admin/community
     * Show all community photos for admin
     */
    public function index()
    {
        $photos = CommunityPhoto::ordered()->get();

        // Add full URL for each photo
        $photos->transform(function ($photo) {
            $photo->image_url = asset('storage/' . $photo->image);
            return $photo;
        });

        return Inertia::render('Admin/AdminCommunity', [
            'photos' => $photos
        ]);
    }

    /**
     * POST /admin/community
     * Upload new community photo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'caption' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);


        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('community', 'public');
            $validated['image'] = $path;
        }

        $photo = CommunityPhoto::create($validated);
        $photo->image_url = asset('storage/' . $photo->image);

        return response()->json([
            'message' => 'Photo uploaded successfully.',
            'photo' => $photo,
        ], 201);
    }

    /**
     * PUT /admin/community/{id}
     * Update photo caption, order, or active status
     */
    public function update(Request $request, $id)
    {
        $photo = CommunityPhoto::findOrFail($id);

        $validated = $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);


        if ($request->hasFile('image')) {
            // Delete old image
            if ($photo->image && Storage::disk('public')->exists($photo->image)) {
                Storage::disk('public')->delete($photo->image);
            }
            
            $path = $request->file('image')->store('community', 'public');
            $validated['image'] = $path;
        }

        $photo->update($validated);
        
        $photo->image_url = asset('storage/' . $photo->image);

        return response()->json([
            'message' => 'Photo updated successfully.',
            'photo' => $photo,
        ]);
    }

    /**
     * DELETE /admin/community/{id}
     * Delete photo
     */
    public function destroy($id)
    {
        $photo = CommunityPhoto::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($photo->image)) {
            Storage::disk('public')->delete($photo->image);
        }

        $photo->delete();

        return response()->json([
            'message' => 'Photo deleted successfully.',
        ]);
    }

    /**
     * GET /community/photos
     * Get active community photos for public display
     */
    public function getActive()
    {
        $photos = CommunityPhoto::active()->ordered()->get();

        // Add full URL for each photo
        $photos->transform(function ($photo) {
            $photo->image_url = asset('storage/' . $photo->image);
            return $photo;
        });

        return response()->json($photos);
    }
}
