<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description',
        'price', 'stock', 'image', 'is_available'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function decrementStock($qty)
    {
        $this->decrement('stock', $qty);
        
        // Otomatis set is_available = false kalau stok habis
        if ($this->stock <= 0) {
            $this->update(['is_available' => false, 'stock' => 0]);
        }
    }
}