<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('user')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Blog/Blog', [
            'blogs' => $blogs
        ]);
    }

    public function show($slug)
    {
        $blog = Blog::with('user')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return Inertia::render('Blog/Show', [
            'blog' => $blog
        ]);
    }
}