<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'customer_name',
        'status',
        'total',
        'notes',
    ];
 
    // Status pesanan: pending, proses, selesai, batal
    const STATUS_PENDING  = 'pending';
    const STATUS_PROSES   = 'proses';
    const STATUS_SELESAI  = 'selesai';
    const STATUS_BATAL    = 'batal';
 
    /**
     * Relasi ke item-item dalam pesanan ini
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
 
    /**
     * Scope: pesanan hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('created_at', today());
    }
 
    /**
     * Scope: hanya pesanan yang selesai
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', self::STATUS_SELESAI);
    }

}
