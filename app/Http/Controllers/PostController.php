<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * GET /posts
     * Return JSON semua post (latest first)
     */
    public function index()
    {
        // Public view: Only show APPROVED posts
        $posts = Post::approved()->latest()->get();

        // Tambahkan full URL gambar supaya gampang dipakai di frontend
        $posts->transform(function ($post) {
            $post->image_url = asset('storage/' . $post->image);
            return $post;
        });

        return response()->json($posts);
    }

    /**
     * POST /posts
     * Store post baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tag'   => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'], // max 4MB
        ]);

        if ($request->hasFile('image')) {
            // Simpan di storage/app/public/posts
            $path = $request->file('image')->store('posts', 'public'); // menghasilkan "posts/namafile.jpg"
            $validated['image'] = $path;
        }

        // optional user relationship (nullable)
        $validated['user_id'] = Auth::id();

        $post = Post::create($validated);

        $post->image_url = asset('storage/' . $post->image);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Post created successfully.',
                'post'    => $post,
            ], 201);
        }

        return redirect()->back()->with('status', 'Post created.');
    }

    /**
     * GET /admin/posts
     * Get all posts for admin with filtering
     */
    public function getPending(Request $request)
    {
        // Fetch all posts for client-side filtering or server-side if needed
        $query = Post::query()->latest();

        // Filter by status if provided (optional, if we want server-side filtering)
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by category if provided
        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        $posts = $query->get();

        // Add image URL
        $posts->transform(function ($post) {
            $post->image_url = asset('storage/' . $post->image);
            return $post;
        });

        return Inertia::render('Admin/AdminPosts', [
            'posts' => $posts
        ]);
    }

    /**
     * PUT /admin/posts/{id}
     * Update a post (title and tag only)
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'tag'   => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],

        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && \Storage::disk('public')->exists($post->image)) {
                \Storage::disk('public')->delete($post->image);
            }
            
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $post->update($validated);
        
        // Add full URL to the response
        $post->image_url = asset('storage/' . $post->image);

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $post,
        ]);
    }

    /**
     * POST /admin/posts/{id}/approve
     * Approve a post
     */
    public function approve($id)
    {
        $post = Post::findOrFail($id);
        $post->status = Post::STATUS_APPROVED;
        $post->save();

        return response()->json([
            'message' => 'Post approved successfully.',
            'post' => $post,
        ]);
    }

    /**
     * POST /admin/posts/{id}/reject
     * Reject a post
     */
    public function reject($id)
    {
        $post = Post::findOrFail($id);
        $post->status = Post::STATUS_REJECTED;
        $post->save();

        return response()->json([
            'message' => 'Post rejected successfully.',
            'post' => $post,
        ]);
    }
}