<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class PaymentVerifController extends Controller
{
    /**
     * List semua pembayaran yang menunggu verifikasi
     */
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('payment_status', 'waiting_verification')
            ->orderBy('updated_at', 'desc')
            ->get();
 
        return view('kasir.payment-verif', compact('orders'));
    }
 
    /**
     * Kasir approve atau reject bukti pembayaran
     */
    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'action'       => 'required|in:approve,reject',
            'payment_note' => 'nullable|string|max:255',
        ]);

        $message = '';
        
        DB::transaction(function () use ($request, $order, &$message) {
            if ($request->action === 'approve') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_note'   => $request->payment_note,
                ]);

                $order->load('items');

                if (!Transaction::where('order_id', $order->id)->exists()) {
                    $transaction = Transaction::create([
                        'user_id'  => auth()->id(),
                        'order_id' => $order->id,
                        'total'    => $order->total,
                        'paid'     => $order->total,
                        'change'   => 0,
                        'metode'   => $order->payment_method ?? 'Cash',
                    ]);

                    foreach ($order->items as $item) {
                        TransactionItem::create([
                            'transaction_id' => $transaction->id,
                            'product_id'     => $item->product_id,
                            'qty'            => $item->qty,
                            'price'          => $item->price,
                        ]);

                        $product = Product::find($item->product_id);
                        if ($product) {
                            $product->decrementStock($item->qty);
                        }
                    }
                }
                $message = 'Pembayaran diverifikasi! Pesanan masuk antrian proses dan transaksi dibuat.';
            } else {
                $order->update([
                    'payment_status' => 'rejected',
                    'payment_proof'  => null,
                    'payment_note'   => $request->payment_note ?? 'Bukti pembayaran tidak valid.',
                ]);
                $message = 'Pembayaran ditolak, customer perlu upload ulang.';
            }
        });
 
        return back()->with('success', $message);
    }
}
