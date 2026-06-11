<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
 
class PaymentController extends Controller
{
    /**
     * Halaman pembayaran - muncul setelah pesanan status "ready"
     */
    public function show(Order $order)
    {
        if (session('customer_order_id') !== $order->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load('items.product');
        return view('customer.payment', compact('order'));
    }
 
    /**
     * Customer upload bukti pembayaran
     */
    public function upload(Request $request, Order $order)
    {
        if (session('customer_order_id') !== $order->id) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'payment_proof' => 'required_unless:payment_method,Cash|image|mimes:jpg,jpeg,png|max:2048',
            'payment_method' => 'required|in:QRIS,Transfer,Cash',
        ]);
 
        $path = null;
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
        }
 
        $order->update([
            'payment_proof'   => $path,
            'payment_method'  => $request->payment_method,
            'payment_status'  => 'waiting_verification',
        ]);
 
        return redirect()->route('order.status', $order)
            ->with('success', 'Bukti pembayaran berhasil dikirim, menunggu verifikasi kasir.');
    }
}
