<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Events\OrderPaidEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    /**
     * Handle incoming webhook notification from Midtrans.
     * URL: POST /midtrans/notification
     */
    public function handle(Request $request)
    {
        // 1. Ambil data notifikasi
        $payload = $request->all();

        // Log untuk debugging
        Log::info('Midtrans Notification Received', $payload);

        // 2. Extract Data Penting
        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;

        // 3. Validasi Field Wajib
        if (!$orderId || !$transactionStatus || !$signatureKey) {
            Log::warning('Midtrans Notification: Missing required fields', $payload);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 4. VALIDASI SIGNATURE KEY
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 5. Cari Order di Database
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            Log::warning("Midtrans Notification: Order not found", ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // 6. IDEMPOTENCY CHECK
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            Log::info("Midtrans Notification: Order already processed", ['order_id' => $orderId]);
            return response()->json(['message' => 'Order already processed'], 200);
        }

        // 7. Update Data Tambahan di Payment Record
        $payment = $order->payment; // Pastikan relasi 'payment' ada di model Order
        if ($payment) {
            $payment->update([
                'midtrans_transaction_id' => $transactionId,
                'payment_type'            => $paymentType,
                'raw_response'            => json_encode($payload),
            ]);
        }

        // 8. MAPPING STATUS TRANSAKSI
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $this->handlePending($order, $payment, 'Menunggu review fraud');
                } else {
                    $this->handleSuccess($order, $payment);
                }
                break;

            case 'settlement':
                $this->handleSuccess($order, $payment);
                break;

            case 'pending':
                $this->handlePending($order, $payment, 'Menunggu pembayaran');
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $this->handleFailed($order, $payment, $transactionStatus);
                break;

            case 'refund':
            case 'partial_refund':
                $this->handleRefund($order, $payment);
                break;
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }

    /**
     * Handle pembayaran sukses.
     */
    protected function handleSuccess(Order $order, ?Payment $payment): void
    {
        Log::info("Payment SUCCESS for Order: {$order->order_number}");

        // Update Order sesuai kolom di model kamu
        $order->update([
            'status'         => 'processing', 
            'payment_status' => 'paid',      // ✅ Tambahkan ini agar payment_status terupdate
        ]);

        // Update Payment
        if ($payment) {
            $payment->update([
                'status'  => 'success',
                'paid_at' => now(),
            ]);
        }

        // Trigger Event
        event(new OrderPaidEvent($order));
    }

    /**
     * Handle pembayaran pending.
     */
    protected function handlePending(Order $order, ?Payment $payment, string $message = ''): void
    {
        Log::info("Payment PENDING for Order: {$order->order_number}", ['message' => $message]);

        $order->update([
            'status'         => 'pending',
            'payment_status' => 'pending',
        ]);

        if ($payment) {
            $payment->update(['status' => 'pending']);
        }
    }

    /**
     * Handle pembayaran gagal/expired/cancelled.
     */
    protected function handleFailed(Order $order, ?Payment $payment, string $reason = ''): void
    {
        Log::info("Payment FAILED for Order: {$order->order_number}", ['reason' => $reason]);

        $order->update([
            'status'         => 'cancelled',
            'payment_status' => 'failed',
        ]);

        if ($payment) {
            $payment->update(['status' => 'failed']);
        }

        // RESTOCK LOGIC
        if ($order->items) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }
    }

    /**
     * Handle refund.
     */
    protected function handleRefund(Order $order, ?Payment $payment): void
    {
        Log::info("Payment REFUNDED for Order: {$order->order_number}");

        $order->update(['payment_status' => 'refunded']);

        if ($payment) {
            $payment->update(['status' => 'refunded']);
        }
    }
}