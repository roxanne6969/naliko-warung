<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_name',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Relasi ke pesanan induknya
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Hitung subtotal otomatis sebelum disimpan
     */
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->price * $item->quantity;
        });
    }
}