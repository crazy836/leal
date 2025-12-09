<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Running Shoes',
                'slug' => 'running-shoes',
                'description' => 'Performance running shoes for all distances',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Basketball Shoes',
                'slug' => 'basketball-shoes',
                'description' => 'High-performance basketball footwear',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Casual Sneakers',
                'slug' => 'casual-sneakers',
                'description' => 'Stylish sneakers for everyday wear',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}