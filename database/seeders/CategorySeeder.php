<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics' => 'Electronic devices and accessories',
            'Clothing' => 'Fashion and apparel',
            'Food & Beverages' => 'Consumable items and drinks',
            'Home & Garden' => 'Home decor and gardening supplies',
            'Sports & Fitness' => 'Sports equipment and fitness gear',
            'Books & Stationery' => 'Books, notebooks and office supplies',
            'Health & Beauty' => 'Personal care and beauty products',
            'Toys & Games' => 'Entertainment and recreational items',
            'Automotive' => 'Car parts and accessories',
            'Pet Supplies' => 'Pet food and accessories'
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description
            ]);
        }
    }
}
