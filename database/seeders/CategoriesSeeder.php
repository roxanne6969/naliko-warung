<?php

namespace Database\Seeders;

use App\Models\CategoriesItem;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Makanan Berat',
            'Makanan Ringan',
            'Minuman Hangat',
            'Minuman Dingin',
            'Dessert & Kue',
            'Cemilan Pedas',
        ];

        foreach ($categories as $name) {
            CategoriesItem::create([
                'category_name' => $name,
            ]);
        }
    }
}
