<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Kopi Susu Aksara',
                'description' => 'Paduan espresso, susu krim, dan gula aren pilihan.',
                'price' => 25000,
                'category' => 'coffee',
                'img' => 'menu_kopi_susu.png',
                'stock' => 48
            ],
            [
                'id' => 2,
                'name' => 'Emerald Matcha Espresso',
                'description' => 'Matcha premium Jepang dengan layer espresso.',
                'price' => 32000,
                'category' => 'coffee',
                'img' => 'menu_matcha_espresso.png',
                'stock' => 40
            ],
            [
                'id' => 3,
                'name' => 'Classic Cappuccino',
                'description' => 'Cappuccino dengan taburan bubuk cokelat.',
                'price' => 28000,
                'category' => 'coffee',
                'img' => 'menu_cappuccino.png',
                'stock' => 45
            ],
            [
                'id' => 4,
                'name' => 'Pure Matcha Latte',
                'description' => 'Susu dan matcha murni yang creamy.',
                'price' => 28000,
                'category' => 'non-coffee',
                'img' => 'hero_coffee.png',
                'stock' => 30
            ],
            [
                'id' => 5,
                'name' => 'Green Apple Mojito',
                'description' => 'Mocktail segar dengan perasan apel hijau dan mint.',
                'price' => 25000,
                'category' => 'non-coffee',
                'img' => 'hero_coffee.png',
                'stock' => 35
            ],
            [
                'id' => 6,
                'name' => 'Signature Chocolate',
                'description' => 'Cokelat pekat yang menenangkan.',
                'price' => 26000,
                'category' => 'non-coffee',
                'img' => 'hero_coffee.png',
                'stock' => 40
            ],
            [
                'id' => 7,
                'name' => 'Matcha Brownies',
                'description' => 'Brownies fudge dengan topping lumeran matcha.',
                'price' => 20000,
                'category' => 'snack',
                'img' => 'hero_coffee.png',
                'stock' => 20
            ],
            [
                'id' => 8,
                'name' => 'Butter Croissant',
                'description' => 'Croissant hangat yang renyah di luar, lembut di dalam.',
                'price' => 18000,
                'category' => 'snack',
                'img' => 'hero_coffee.png',
                'stock' => 15
            ],
            [
                'id' => 9,
                'name' => 'Aksara Mix Platter',
                'description' => 'Sosis, kentang goreng, dan nugget.',
                'price' => 35000,
                'category' => 'snack',
                'img' => 'hero_coffee.png',
                'stock' => 25
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['id' => $product['id']], $product);
        }
    }
}
