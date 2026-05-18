<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Nasi Goreng',
                'description' => 'Nasi goreng spesial dengan telur',
                'price' => 15000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_id' => 1,
                'name' => 'Mie Goreng',
                'description' => 'Mie goreng spesial',
                'price' => 13000,
                'stock' => 50,
                'is_available' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Es Teh Manis',
                'description' => 'Teh manis dingin segar',
                'price' => 5000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => 2,
                'name' => 'Es Jeruk',
                'description' => 'Jeruk peras dingin segar',
                'price' => 7000,
                'stock' => 100,
                'is_available' => true,
            ],
            [
                'category_id' => 3,
                'name' => 'Kerupuk',
                'description' => 'Kerupuk renyah',
                'price' => 2000,
                'stock' => 200,
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}