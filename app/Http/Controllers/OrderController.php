<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.menu')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return Inertia::render('Order/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        // Cek apakah user yang login adalah member (berdasarkan nomor HP)
        $isMember = $user && $user->phone_number 
            ? \App\Models\Member::where('phone', $user->phone_number)->where('status', 'approved')->exists() 
            : false;

        $menus = Menu::orderBy('stock', 'desc')->get()->map(function ($menu) use ($isMember) {
            // Jika bukan member, diskon dianggap 0. Sekarang pakai logika stok > 0.
            $discountPercent = $isMember && $menu->stock > 0 ? (int)$menu->discount_percent : 0;
            $originalPrice   = (float)$menu->price;
            $discountedPrice = $discountPercent > 0
                ? round($originalPrice * (1 - $discountPercent / 100))
                : $originalPrice;

            return [
                'id'               => $menu->id,
                'category_id'      => $menu->category_id,
                'name'             => $menu->name,
                'price'            => $originalPrice,
                'discount_percent' => $discountPercent,
                'discounted_price' => $discountedPrice,
                'description'      => $menu->description,
                'image_url'        => $menu->image ? asset('storage/' . $menu->image) : null,
                'image_position'   => $menu->image_position,
                'image_zoom'       => (float)$menu->image_zoom,
                'special_type'     => $menu->special_type,
                'stock'            => (int)$menu->stock, // KIRIM DATA STOK KE USER
                'is_available'     => (int)$menu->stock > 0,
            ];
        });

        return Inertia::render('Order/Create', [
            'menus' => $menus,
            'isMember' => $isMember // Kirim status member ke frontend
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,qris',
            'table_number' => 'required|integer|min:1|max:20',
        ]);

        $calculatedTotal = 0;
        $orderItemsData = [];

        $user = Auth::user();
        $isMember = $user && $user->phone_number 
            ? \App\Models\Member::where('phone', $user->phone_number)->where('status', 'approved')->exists() 
            : false;

        foreach ($request->items as $item) {
            $menu = Menu::where('id', $item['id'])->firstOrFail();

            if ($menu->stock <= 0) {
                return redirect()->back()->with('error', "Stok menu '{$menu->name}' sudah habis!");
            }

            if ($menu->stock < $item['quantity']) {
                return redirect()->back()->with('error', "Stok menu '{$menu->name}' tidak cukup! (Sisa: {$menu->stock})");
            }

            // Hitung harga setelah diskon di sisi server (hanya jika Member)
            $price = (float) $menu->price;
            $discount = $isMember ? (int) $menu->discount_percent : 0;
            $finalItemPrice = $discount > 0 
                ? round($price * (1 - $discount / 100)) 
                : $price;

            $subtotal = $finalItemPrice * $item['quantity'];
            $calculatedTotal += $subtotal;

            $orderItemsData[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $finalItemPrice,
            ];

            // Decrement Stock
            $menu->decrement('stock', $item['quantity']);
            if ($menu->stock < 0) {
                $menu->update(['stock' => 0]);
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $calculatedTotal,
            'payment_method' => $request->payment_method,
            'table_number' => $request->table_number,
            'status' => 'proses'
        ]);

        foreach ($orderItemsData as $itemData) {
            $order->items()->create($itemData);
        }

        // MIDTRANS LOGIC
        $snapToken = null;
        if ($request->payment_method !== 'cash') {
            try {
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = true;
                Config::$is3ds = true;

                $midtransOrderId = 'UTARA-' . $order->id . '-' . time();
                $params = [
                    'transaction_details' => [
                        'order_id' => $midtransOrderId,
                        'gross_amount' => (int)$calculatedTotal,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => Auth::user()->phone_number,
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);
                $order->update([
                    'snap_token' => $snapToken,
                    'midtrans_order_id' => $midtransOrderId
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Midtrans Snap Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal membuat invoice digital: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.index')->with([
            'success' => 'Pesanan berhasil dibuat!',
            'snap_token' => $snapToken
        ]);
    }

    public function specials()
    {
        $weekly = Menu::where('special_type', 'weekly')->where('is_available', true)->get();
        $monthly = Menu::where('special_type', 'monthly')->where('is_available', true)->get();

        return Inertia::render('Order/Specials', [
            'weekly' => $weekly,
            'monthly' => $monthly
        ]);
    }
}
