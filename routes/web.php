<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommunityPhotoController;
use App\Http\Controllers\CrewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SubcategoryController;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;
use Carbon\Carbon;

// ======================
// PUBLIC ROUTES
// ======================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);
Route::get('/menu', [HomeController::class, 'index']);
Route::get('/tetangga', [HomeController::class, 'index']);
Route::get('/promo', [PromoController::class, 'index'])->name('promo');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/promo-loyalty', fn () => Inertia::render('PromoLoyalty'))->name('promo-loyalty');

// CSRF token endpoint for frontend fetch fallback
Route::get('/csrf-token', fn () => response()->json([
    'csrf_token' => csrf_token(),
]));

// POSTS (public)
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);

// REVIEWS (public)
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews/google', [ReviewController::class, 'google']);

// COMMUNITY PHOTOS (public)
Route::get('/community/photos', [CommunityPhotoController::class, 'getActive']);
Route::get('/crew/photos', [CrewController::class, 'getActive']);
Route::post('/members/search', [MemberController::class, 'search'])->name('members.search');
// ======================
// LOGIN & REGISTER
// ======================
require __DIR__.'/auth.php';

// ======================
// USER DASHBOARD & ORDERS (AUTH REQUIRED)
// ======================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $foodsCount = Menu::where('category_id', 1)->count();
        $drinksCount = Menu::where('category_id', 2)->count();
        $totalSubcategories = \App\Models\Subcategory::count();

        return Inertia::render('Dashboard', [
            'foodsCount' => $foodsCount,
            'drinksCount' => $drinksCount,
            'totalSubcategories' => $totalSubcategories,
        ]);
    })->name('dashboard');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/specials', [\App\Http\Controllers\OrderController::class, 'specials'])->name('orders.specials');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/apply-member', [\App\Http\Controllers\MemberController::class, 'apply'])->name('member.apply');
});

// ======================
// ADMIN (AUTH REQUIRED)
// ======================
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin', function () {
        $foodsCount = Menu::where('category_id', 1)->count();
        $drinksCount = Menu::where('category_id', 2)->count();

        // Data Penjualan 6 Bulan Terakhir
        $salesData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->translatedFormat('M'); // 'Jan', 'Feb', dst.
            
            $total = Order::whereIn('status', ['success', 'proses'])
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_price');

            $salesData[] = [
                'label' => $monthName,
                'value' => (float)$total
            ];
        }

        return Inertia::render('Admin/HomeAdmin', [
            'foodsCount' => $foodsCount,
            'drinksCount' => $drinksCount,
            'totalSubcategories' => \App\Models\Subcategory::count(),
            'salesData' => $salesData,
        ]);
    })->name('admin.home');

    // Menu
    Route::get('/admin/menu', function (Request $request) {
        $tab = $request->query('tab', 'foods');
        $formatMenu = function($menu) {
            $menu->image = $menu->image ? asset('storage/' . $menu->image) : null;
            return $menu;
        };

        return Inertia::render('Admin/Menu', [
            'foodsMenus' => Menu::where('category_id', 1)->orderBy('is_available', 'desc')->get()->map($formatMenu),
            'drinksMenus' => Menu::where('category_id', 2)->orderBy('is_available', 'desc')->get()->map($formatMenu),
            'foodsSubcategories' => Subcategory::where('category_id', 1)->orderBy('order')->get(),
            'drinksSubcategories' => Subcategory::where('category_id', 2)->orderBy('order')->get(),
            'tab' => $tab,
        ]);
    })->name('admin.menu');

    Route::post('/admin/menu/store', [MenuController::class, 'store'])
        ->name('admin.menu.store');
    Route::put('/admin/menu/{menu}', [MenuController::class, 'update'])
        ->name('admin.menu.update');
    Route::patch('/admin/menu/{menu}/toggle-availability', [MenuController::class, 'toggleAvailability'])
        ->name('admin.menu.toggle');

    Route::post('/admin/menu/reset-all', [\App\Http\Controllers\Admin\MenuResetController::class, 'resetAll'])
        ->name('admin.menu.reset-all');

    Route::delete('/admin/menu/{menu}', [MenuController::class, 'destroy'])
        ->name('admin.menu.destroy');
    // ============================
    // SUBCATEGORY PAGE (INDEX)
    // ============================
    Route::get('/admin/subcategory', [SubcategoryController::class, 'index'])
        ->name('admin.subcategory');

    // SubCategories CRUD
    Route::post('/admin/subcategories', [SubcategoryController::class, 'store']);
    Route::put('/admin/subcategories/{id}', [SubcategoryController::class, 'update']);
    Route::delete('/admin/subcategories/{id}', [SubcategoryController::class, 'destroy']);

    // Users
    Route::get('/admin/users', fn() => Inertia::render('Admin/Users'))
        ->name('admin.users');

    // Banners
    Route::get('/admin/banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('admin.banners.index');
    Route::post('/admin/banners', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('admin.banners.store');
    Route::delete('/admin/banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('admin.banners.destroy');

    // Posts Management
    Route::get('/admin/posts', [PostController::class, 'getPending'])->name('admin.posts.index');
    Route::put('/admin/posts/{id}', [PostController::class, 'update'])->name('admin.posts.update');
    Route::post('/admin/posts/{id}/approve', [PostController::class, 'approve'])->name('admin.posts.approve');
    Route::post('/admin/posts/{id}/reject', [PostController::class, 'reject'])->name('admin.posts.reject');

    // Community Photos Management
    Route::get('/admin/community', [CommunityPhotoController::class, 'index'])->name('admin.community.index');
    Route::post('/admin/community', [CommunityPhotoController::class, 'store'])->name('admin.community.store');
    Route::put('/admin/community/{id}', [CommunityPhotoController::class, 'update'])->name('admin.community.update');
    Route::delete('/admin/community/{id}', [CommunityPhotoController::class, 'destroy'])->name('admin.community.destroy');

    // Crew Management
    Route::get('/admin/crews', [CrewController::class, 'index'])->name('admin.crew.index');
    Route::post('/admin/crews', [CrewController::class, 'store'])->name('admin.crew.store');
    Route::put('/admin/crews/{id}', [CrewController::class, 'update'])->name('admin.crew.update');
    Route::delete('/admin/crews/{id}', [CrewController::class, 'destroy'])->name('admin.crew.destroy');

    // Member Management
    Route::get('/admin/members', [MemberController::class, 'index'])->name('admin.members.index');
    Route::post('/admin/members', [MemberController::class, 'store'])->name('admin.members.store');
    Route::put('/admin/members/{id}', [MemberController::class, 'update'])->name('admin.members.update');
    Route::post('/admin/members/{id}/redeem', [MemberController::class, 'redeem'])->name('admin.members.redeem');
    Route::delete('/admin/members/{id}', [MemberController::class, 'destroy'])->name('admin.members.destroy');
    Route::patch('/admin/members/{id}/approve', [MemberController::class, 'approve'])->name('admin.members.approve');
    Route::patch('/admin/members/{id}/reject', [MemberController::class, 'reject'])->name('admin.members.reject');

    // Blog Management
    Route::get('/admin/blogs', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('admin.blogs.index');
    Route::get('/admin/blogs/create', [\App\Http\Controllers\Admin\BlogController::class, 'create'])->name('admin.blogs.create');
    Route::get('/admin/blogs/{id}/edit', [\App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('admin.blogs.edit');
    Route::post('/admin/blogs', [\App\Http\Controllers\Admin\BlogController::class, 'store'])->name('admin.blogs.store');
    Route::post('/admin/blogs/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('admin.blogs.update');
    Route::delete('/admin/blogs/{id}', [\App\Http\Controllers\Admin\BlogController::class, 'destroy'])->name('admin.blogs.destroy');

    // Review Management
    Route::get('/admin/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index');
    Route::put('/admin/reviews/{review}', [ReviewController::class, 'updateStatus'])->name('admin.reviews.update');
    Route::delete('/admin/reviews/{review}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // Order Management
    Route::get('/admin/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::put('/admin/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');
    Route::delete('/admin/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');
});
 
// MIDTRANS WEBHOOK
Route::post('/midtrans/notification', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);
Route::get('/debug-db', function () {
    return [
        'host' => env('DB_HOST'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
    ];
});