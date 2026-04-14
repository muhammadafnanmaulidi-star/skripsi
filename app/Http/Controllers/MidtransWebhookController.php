<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$serverKey = $serverKey;
        Config::$isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));

        try {
            $notification = new Notification();
            
            // Verify Signature
            $signatureMatch = hash('sha512', 
                $notification->order_id . 
                $notification->status_code . 
                $notification->gross_amount . 
                $serverKey
            );

            if ($signatureMatch !== $notification->signature_key) {
                \Log::warning('Midtrans Webhook: Invalid signature', [
                    'order_id' => $notification->order_id,
                    'remote_ip' => $request->ip()
                ]);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // Parse order ID (format typically: UTARA-{id}-{timestamp})
            $orderId = $notification->order_id;
            if (strpos($orderId, 'UTARA-') === 0) {
                $parts = explode('-', $orderId);
                $dbOrderId = $parts[1] ?? $orderId;
            } else {
                $dbOrderId = $orderId;
            }
            
            $order = Order::find($dbOrderId);

            if (!$order) {
                \Log::error('Midtrans Webhook: Order not found', ['order_id' => $dbOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $fraud = $notification->fraud_status;

            \Log::info('Midtrans Webhook Received', [
                'order_id' => $order->id,
                'status' => $transaction,
                'type' => $type
            ]);

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $order->update(['status' => 'proses']);
                    } else {
                        $order->update(['status' => 'success']);
                    }
                }
            } else if ($transaction == 'settlement') {
                $order->update(['status' => 'success']);
            } else if ($transaction == 'pending') {
                $order->update(['status' => 'proses']);
            } else if ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                $order->update(['status' => 'cancel']);
            }

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            \Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}

