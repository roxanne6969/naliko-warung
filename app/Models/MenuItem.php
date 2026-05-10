<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'is_available',
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(CategoriesItem::class, 'category_id');
    }
}
