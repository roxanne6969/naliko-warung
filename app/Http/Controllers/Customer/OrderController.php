<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function cart()
    {
        return view('customer.cart');
    }

    public function store(Request $request)
    {
        $items = $request->items;
        if (!$items || count($items) === 0) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong']);
        }

        $total = 0;
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if ($product) $total += $product->price * $item['qty'];
        }

        $order = Order::create([
            'customer_name' => $request->customer_name,
            'table_number' => $request->table_number,
            'note' => $request->note,
            'status' => 'pending',
            'total' => $total,
        ]);

        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $product->price,
                ]);
            }
        }

        return response()->json(['success' => true, 'order_id' => $order->id]);
    }

    public function status(Order $order)
    {
        $order->load('items.product');
        return view('customer.status', compact('order'));
    }
}