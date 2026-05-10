<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriesItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category_name',
    ];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }
}
