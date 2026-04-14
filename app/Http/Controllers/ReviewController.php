<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reviews = Review::where('status', 'approved')
            ->latest()
            ->get()
            ->map(function ($review) {
                if ($review->avatar_url) {
                    $review->avatar_url = Storage::url($review->avatar_url);
                }

                return $review;
            });

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        \Illuminate\Support\Facades\Log::info('Review submission attempt', $request->all());
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:50', 'regex:/^[^0-9]*$/'],
                'title' => ['nullable', 'string', 'max:120'],
                'rating' => ['required', 'numeric', 'min:1', 'max:5'],
                'message' => ['required', 'string', 'max:1000'],
                'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            ]);

            if ($request->hasFile('avatar')) {
                $data['avatar_url'] = $request->file('avatar')->store('reviews', 'public');
            }

            unset($data['avatar']);
            $data['status'] = 'pending';

            $review = Review::create($data);

            if ($review->avatar_url) {
                $review->avatar_url = Storage::url($review->avatar_url);
            }

            return response()->json($review, 201);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Review submission failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'message' => 'Terjadi kesalahan pada server.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get reviews from Google Maps Place Details API.
     * Uses caching to reduce API costs - only fetches from Google every 24 hours.
     */
    public function google(): JsonResponse
    {
        $placeId = config('services.google.place_id', env('GOOGLE_PLACE_ID'));
        $apiKey = config('services.google.places_api_key', env('GOOGLE_PLACES_API_KEY'));

        if (!$placeId || !$apiKey) {
            return response()->json([
                'message' => 'Google Place ID atau API Key belum diatur.',
            ], 500);
        }

        // Check if we have cached data that's still fresh (less than 24 hours old)
        $cache = \App\Models\GoogleReviewCache::where('place_id', $placeId)
            ->where('fetched_at', '>', now()->subHours(24))
            ->first();

        if ($cache) {
            // Return cached data - NO API CALL TO GOOGLE!
            return response()->json($cache->reviews_data);
        }

        // Cache is old or doesn't exist, fetch fresh data from Google
        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
            'place_id' => $placeId,
            'fields' => 'name,rating,user_ratings_total,reviews',
            'reviews_no_translations' => true,
            'language' => 'id',
            'key' => $apiKey,
        ]);

        if (!$response->ok()) {
            return response()->json([
                'message' => 'Gagal mengambil data dari Google Places.',
                'status' => $response->status(),
            ], 502);
        }

        $payload = $response->json();
        if (($payload['status'] ?? '') !== 'OK') {
            return response()->json([
                'message' => 'Google Places mengembalikan status tidak OK.',
                'google_status' => $payload['status'] ?? null,
                'error_message' => $payload['error_message'] ?? null,
            ], 502);
        }

        // Transform reviews to our format
        $reviews = collect($payload['result']['reviews'] ?? [])->map(function ($review) {
            return [
                'id' => $review['author_url'] ?? $review['author_name'] ?? null,
                'name' => $review['author_name'] ?? 'Anonymous',
                'title' => $review['relative_time_description'] ?? 'Google Reviewer',
                'rating' => $review['rating'] ?? 0,
                'message' => $review['text'] ?? '',
                'avatar_url' => $review['profile_photo_url'] ?? null,
            ];
        })->toArray();

        // Save to cache for next 24 hours
        \App\Models\GoogleReviewCache::updateOrCreate(
            ['place_id' => $placeId],
            [
                'reviews_data' => $reviews,
                'fetched_at' => now(),
            ]
        );

        return response()->json($reviews);
    }
    public function adminIndex()
    {
        $reviews = Review::latest()->get()->map(function ($review) {
            if ($review->avatar_url) {
                $review->avatar_url = Storage::url($review->avatar_url);
            }
            return $review;
        });

        return Inertia::render('Admin/Reviews', [
            'reviews' => $reviews
        ]);
    }

    public function updateStatus(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $review->update(['status' => $data['status']]);

        return back()->with('success', 'Status review berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        if ($review->avatar_url) {
            Storage::disk('public')->delete($review->avatar_url);
        }
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }
}
