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
        $request->validate([
            'status' => 'required|in:pending,confirmed,ready,done',
        ]);

        $order->update(['status' => $request->status]);

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

        $metode = $request->metode ?? 'Cash';
        if (!in_array($metode, ['Cash', 'QRIS'])) {
            return response()->json(['success' => false, 'message' => 'Metode pembayaran tidak valid']);
        }

        try {
            $transaction_id = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $items, $metode) {
                // Validasi stok dan hitung total
                $total = 0;
                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    if (!$product || $product->stock < $item['qty']) {
                        throw new \Exception('Stok ' . ($product->name ?? 'produk') . ' tidak mencukupi!');
                    }
                    $total += $product->price * $item['qty'];
                }

                // Validasi pembayaran berdasarkan metode
                if ($metode === 'Cash') {
                    $paid = intval($request->paid);
                    if ($paid < $total) {
                        throw new \Exception('Uang bayar kurang! Total: Rp ' . number_format($total, 0, ',', '.') . ', Dibayar: Rp ' . number_format($paid, 0, ',', '.'));
                    }
                } else {
                    // QRIS : paid = total (uang pas, tidak ada kembalian)
                    $paid = $total;
                }

                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'order_id' => $request->order_id ?? null,
                    'total' => $total,
                    'paid' => $paid,
                    'change' => $paid - $total,
                    'metode' => $metode,
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

    public function stok(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();
        $categories = \App\Models\Category::all();

        return view('kasir.stok', compact('products', 'categories'));
    }
}