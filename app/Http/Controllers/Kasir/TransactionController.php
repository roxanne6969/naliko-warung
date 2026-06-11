<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->with('category')->get();
        return view('kasir.transaction', compact('products'));
    }

    public function orders()
    {
        $orders = Order::with('items.product')
            ->whereIn('status', ['pending', 'confirmed', 'ready'])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('kasir.orders', compact('orders'));
    }

    public function confirm(Request $request, Order $order)
    {
        $status = $request->status;
        $order->update(['status' => $status]);

        // Catatan: Transaksi & pengurangan stok sudah dilakukan di PaymentVerifController
        // saat kasir memverifikasi pembayaran (Approve).


        return back()->with('success', 'Status pesanan diperbarui!');
    }

    public function store(Request $request)
    {
        $items = $request->items;
        if (!$items || count($items) === 0) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong']);
        }

        try {
            $transaction_id = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $items) {
                // Validate all stock first and calculate actual DB total
                $total = 0;
                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    if (!$product || $product->stock < $item['qty']) {
                        throw new \Exception('Stok ' . ($product->name ?? 'produk') . ' tidak mencukupi!');
                    }
                    $total += $product->price * $item['qty'];
                }

                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'order_id' => $request->order_id ?? null,
                    'total' => $total,
                    'paid' => $request->paid,
                    'change' => $request->paid - $total,
                    'metode' => $request->metode ?? 'Cash',
                ]);

                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                    ]);

                    // Kurangi stok
                    $product->decrementStock($item['qty']);
                }

                return $transaction->id;
            });

            return response()->json(['success' => true, 'transaction_id' => $transaction_id]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    public function history()
    {
        $transactions = Transaction::with('user', 'items.product')
            ->latest()
            ->paginate(10);
        return view('kasir.history', compact('transactions'));
    }
}