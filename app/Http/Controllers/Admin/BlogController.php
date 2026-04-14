<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Blog', [
            'blogs' => Blog::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/BlogForm', [
            'isEditing' => false
        ]);
    }

    public function edit($id)
    {
        return Inertia::render('Admin/BlogForm', [
            'blog' => Blog::findOrFail($id),
            'isEditing' => true
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'required|string|max:255|unique:blogs,title',
            'content' => 'required|string',

            'excerpt' => 'nullable|string',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $path = '/storage/' . $path;
        }

        // Generate slug
        $slug = Str::slug($request->title);
        $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        // Auto generate excerpt if not provided
        $excerpt = $request->excerpt;
        if (!$excerpt) {
            $excerpt = Str::limit(strip_tags($request->content), 150);
        }

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'excerpt' => $excerpt,
            'image' => $path ?? '/blog1.jpg', // Fallback if upload fails but validation passed (?)
            'user_id' => Auth::id() ?? 1, // Fallback to 1 if not logged in (should be protected by auth middleware)
            'is_published' => true,
            'published_at' => now(),
        ]);

        return redirect('/admin/blogs')->with('success', 'Blog berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:blogs,title,' . $id,
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'excerpt' => Str::limit(strip_tags($request->content), 150),
        ];

        // Update Slug if title changed
        if ($blog->title !== $request->title) {
             $slug = Str::slug($request->title);
             $count = Blog::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
             if ($count > 0) {
                 $slug .= '-' . ($count + 1);
             }
             $data['slug'] = $slug;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($blog->image && str_starts_with($blog->image, '/storage/')) {
                 $oldPath = str_replace('/storage/', '', $blog->image);
                 if (Storage::disk('public')->exists($oldPath)) {
                     Storage::disk('public')->delete($oldPath);
                 }
            }
            
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = '/storage/' . $path;
        }

        $blog->update($data);

        return redirect('/admin/blogs')->with('success', 'Blog berhasil diperbarui');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        
        if ($blog->image && str_starts_with($blog->image, '/storage/')) {
            $path = str_replace('/storage/', '', $blog->image);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $blog->delete();

        return redirect()->back()->with('success', 'Blog berhasil dihapus');
    }
}
