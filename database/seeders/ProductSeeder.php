<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories if they don't exist
        $categories = [
            'Running Shoes',
            'Basketball Shoes',
            'Casual Sneakers',
            'Training Shoes',
            'Football Boots'
        ];
        
        foreach ($categories as $categoryName) {
            Category::firstOrCreate(
                ['name' => $categoryName],
                [
                    'description' => 'Description for ' . $categoryName,
                    'slug' => \Illuminate\Support\Str::slug($categoryName)
                ]
            );
        }
        
        // Get all categories
        $allCategories = Category::all();
        
        // Create sample products
        $products = [
            [
                'name' => 'Air Zoom Pegasus 38',
                'slug' => 'air-zoom-pegasus-38',
                'description' => 'Responsive cushioning and a softer forefoot make the Air Zoom Pegasus 38 a perfect daily trainer for any runner.',
                'price' => 129.99,
                'category_id' => $allCategories->firstWhere('name', 'Running Shoes')->id,
                'is_active' => true,
                'sku' => 'AZP38-001'
            ],
            [
                'name' => 'LeBron 19',
                'slug' => 'lebron-19',
                'description' => 'Channeling the force of a powerful dunk, the LeBron 19 gives you court-grabbing traction and responsive cushioning.',
                'price' => 169.99,
                'category_id' => $allCategories->firstWhere('name', 'Basketball Shoes')->id,
                'is_active' => true,
                'sku' => 'LB19-001'
            ],
            [
                'name' => 'Air Force 1 \'07',
                'slug' => 'air-force-1-07',
                'description' => 'The radiance lives on in the Nike Air Force 1 \'07, the b-ball OG that puts a fresh spin on what you know best.',
                'price' => 99.99,
                'category_id' => $allCategories->firstWhere('name', 'Casual Sneakers')->id,
                'is_active' => true,
                'sku' => 'AF107-001'
            ],
            [
                'name' => 'Metcon 7',
                'slug' => 'metcon-7',
                'description' => 'The Nike Metcon 7 is the gold standard for weight training—even shorter distances with a beveled heel for improved traction during rope climbs.',
                'price' => 139.99,
                'category_id' => $allCategories->firstWhere('name', 'Training Shoes')->id,
                'is_active' => true,
                'sku' => 'MC7-001'
            ],
            [
                'name' => 'Mercurial Vapor 14',
                'slug' => 'mercurial-vapor-14',
                'description' => 'The Nike Mercurial Vapor 14 Elite FG wraps your foot in a snug, seamless fit for better touch on the ball.',
                'price' => 149.99,
                'category_id' => $allCategories->firstWhere('name', 'Football Boots')->id,
                'is_active' => true,
                'sku' => 'MV14-001'
            ],
            [
                'name' => 'React Infinity Run Flyknit 2',
                'slug' => 'react-infinity-run-flyknit-2',
                'description' => 'The Nike React Infinity Run Flyknit 2 continues to help keep you running with an updated upper and improved comfort.',
                'price' => 159.99,
                'category_id' => $allCategories->firstWhere('name', 'Running Shoes')->id,
                'is_active' => true,
                'sku' => 'RIRF2-001'
            ],
            [
                'name' => 'KD14',
                'slug' => 'kd14',
                'description' => 'Kevin Durant is a force of nature, and the Nike KD14 helps you play with the same freedom and force as the man himself.',
                'price' => 159.99,
                'category_id' => $allCategories->firstWhere('name', 'Basketball Shoes')->id,
                'is_active' => true,
                'sku' => 'KD14-001'
            ],
            [
                'name' => 'Blazer Mid \'77 Vintage',
                'slug' => 'blazer-mid-77-vintage',
                'description' => 'In the \'70s, Nike was the new kid on the block. The Nike Blazer Mid \'77 Vintage channels the old-school look you love.',
                'price' => 89.99,
                'category_id' => $allCategories->firstWhere('name', 'Casual Sneakers')->id,
                'is_active' => true,
                'sku' => 'BM77V-001'
            ]
        ];
        
        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}