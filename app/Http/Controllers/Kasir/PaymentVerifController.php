<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
 
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
 
        if ($request->action === 'approve') {
            $order->update([
                'payment_status' => 'paid',
                'payment_note'   => $request->payment_note,
            ]);
            $message = 'Pembayaran diverifikasi! Pesanan masuk antrian proses.';
        } else {
            $order->update([
                'payment_status' => 'rejected',
                'payment_proof'  => null,
                'payment_note'   => $request->payment_note ?? 'Bukti pembayaran tidak valid.',
            ]);
            $message = 'Pembayaran ditolak, customer perlu upload ulang.';
        }
 
        return back()->with('success', $message);
    }
}
