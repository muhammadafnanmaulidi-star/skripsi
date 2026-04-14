<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        // Otomatis Sync status dengan Midtrans untuk pesanan yang masih 'proses'
        $pendingOrders = Order::where('status', 'proses')
            ->whereNotNull('midtrans_order_id')
            ->where('created_at', '>', now()->subDays(1))
            ->get();

        if ($pendingOrders->count() > 0) {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');

            foreach ($pendingOrders as $order) {
                try {
                    $status = \Midtrans\Transaction::status($order->midtrans_order_id);
                    $paymentStatus = $status->transaction_status;

                    if ($paymentStatus == 'settlement' || $paymentStatus == 'capture') {
                        $order->update(['status' => 'success']);
                    } else if ($paymentStatus == 'cancel' || $paymentStatus == 'deny' || $paymentStatus == 'expire') {
                        // Jika ternyata expired, kita anggap cancel agar stok kembali jika diperlukan
                        // Tapi untuk saat ini kita fokus ke sukses dulu agar user senang
                    }
                } catch (\Exception $e) {
                    \Log::error("Sync Error for Order {$order->id}: " . $e->getMessage());
                }
            }
        }

        $orders = Order::with(['user', 'items.menu'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Orders', [
            'orders' => $orders
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:proses,success,cancel',
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'cancel') {
            $data['cancel_reason'] = $request->cancel_reason;
        } else {
            // Reset cancel_reason jika status bukan cancel
            $data['cancel_reason'] = null;
        }

        $oldStatus = $order->status;
        $order->update($data);

        // Jika status diubah ke cancel, kembalikan stok
        if ($request->status === 'cancel' && $oldStatus !== 'cancel') {
            foreach ($order->items as $item) {
                if ($item->menu) {
                    $item->menu->increment('stock', $item->quantity);
                    // Jika stok jadi lebih dari 0, pastikan is_available true
                    if ($item->menu->stock > 0 && !$item->menu->is_available) {
                        $item->menu->update(['is_available' => true]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus!');
    }
}
