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

        try {
            $order_id = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $items) {
                $total = 0;
                
                // Validasi stok untuk setiap item
                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    if (!$product || $product->stock < $item['qty']) {
                        throw new \Exception('Stok ' . ($product->name ?? 'produk') . ' tidak mencukupi!');
                    }
                    $total += $product->price * $item['qty'];
                }

                $order = Order::create([
                    'customer_name' => $request->customer_name,
                    'table_number' => $request->table_number,
                    'note' => $request->note,
                    'status' => 'pending',
                    'total' => $total,
                    'payment_status' => 'unpaid',
                ]);

                foreach ($items as $item) {
                    $product = Product::find($item['id']);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'price' => $product->price,
                    ]);
                }

                return $order->id;
            });

            // session untuk customer
            session(['customer_order_id' => $order_id]);

            return response()->json(['success' => true, 'order_id' => $order_id]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function status(Order $order)
    {
        $order->load('items.product');
        return view('customer.status', compact('order'));
    }
}