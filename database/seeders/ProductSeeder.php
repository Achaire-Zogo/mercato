<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $categories = Category::all();

        // Product templates for each category
        $productTemplates = [
            'Electronics' => [
                'names' => ['Smartphone', 'Laptop', 'Headphones', 'Tablet', 'Smart Watch'],
                'price_range' => [199.99, 1999.99],
                'stock_range' => [5, 50],
            ],
            'Clothing' => [
                'names' => ['T-Shirt', 'Jeans', 'Dress', 'Jacket', 'Sweater'],
                'price_range' => [19.99, 199.99],
                'stock_range' => [10, 100],
            ],
            'Food & Beverages' => [
                'names' => ['Coffee', 'Tea', 'Snacks', 'Juice', 'Energy Drink'],
                'price_range' => [2.99, 29.99],
                'stock_range' => [20, 200],
            ],
            'Home & Garden' => [
                'names' => ['Plant Pot', 'Lamp', 'Cushion', 'Vase', 'Picture Frame'],
                'price_range' => [9.99, 99.99],
                'stock_range' => [5, 50],
            ],
            'Sports & Fitness' => [
                'names' => ['Yoga Mat', 'Dumbbells', 'Water Bottle', 'Running Shoes', 'Sports Bag'],
                'price_range' => [14.99, 149.99],
                'stock_range' => [5, 30],
            ],
        ];

        // Create 50 products
        for ($i = 0; $i < 50; $i++) {
            $category = $categories->random();
            $template = $productTemplates[$category->name] ?? [
                'names' => ['Generic Item'],
                'price_range' => [9.99, 99.99],
                'stock_range' => [5, 50],
            ];

            $baseName = $faker->randomElement($template['names']);
            $variant = $faker->word;
            $name = "$baseName $variant";

            $stock = $faker->numberBetween($template['stock_range'][0], $template['stock_range'][1]);
            $alertThreshold = max(5, floor($stock * 0.2)); // 20% of stock or minimum 5

            Product::create([
                'name' => $name,
                'matricule' => strtoupper($faker->bothify('PRD-####-????')),
                'price' => $faker->randomFloat(2, $template['price_range'][0], $template['price_range'][1]),
                'stock_quantity' => $stock,
                'alert_threshold' => $alertThreshold,
                'category_id' => $category->id,
                'image_path' => null, // You can add default images if needed
                'expiration_date' => $category->name === 'Food & Beverages' ?
                    $faker->dateTimeBetween('+1 month', '+1 year') : null
            ]);
        }
    }
}
