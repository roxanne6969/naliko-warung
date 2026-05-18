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
            ->orderBy('created_at', 'desc')
            ->get();
        return view('kasir.orders', compact('orders'));
    }

    public function confirm(Request $request, Order $order)
    {
        $status = $request->status;
        $order->update(['status' => $status]);
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
        ]);

        foreach ($items as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['id'],
                'qty' => $item['qty'],
                'price' => $item['price'],
            ]);

            // Kurangi stok
            Product::find($item['id'])->decrement('stock', $item['qty']);
        }

        return response()->json(['success' => true, 'transaction_id' => $transaction->id]);
    }
}