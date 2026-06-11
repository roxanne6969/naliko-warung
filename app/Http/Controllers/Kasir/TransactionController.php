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

        // When order is marked as done, create a Transaction record
        if ($status === 'done') {
            $order->load('items');

            // Only create transaction if one doesn't already exist for this order
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

                    // Decrement stock for online orders
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stock', $item->qty);
                    }
                }
            }
        }

        return back()->with('success', 'Status pesanan diperbarui!');
    }

    public function store(Request $request)
    {
        $items = $request->items;
        if (!$items || count($items) === 0) {
            return response()->json(['success' => false]);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
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
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);

            // Kurangi stok
            $product = Product::find($item['id']);
            if ($product) {
                $product->decrement('stock', $item['qty']);
            }
        }

        return response()->json(['success' => true, 'transaction_id' => $transaction->id]);
    }
    public function history()
    {
        $transactions = Transaction::with('user', 'items.product')
            ->latest()
            ->paginate(10);
        return view('kasir.history', compact('transactions'));
    }
}