<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class NikeProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all products
        $products = Product::all();
        
        // Local image paths for Nike products (matching the files in public/images)
        $nikeImages = [
            'Air Force 1 \'07' => [
                'images/Air Force 1 \'07.png'
            ],
            'Air Zoom Pegasus 38' => [
                'images/Air Zoom Pegasus 38.png'
            ],
            'Blazer Mid \'77 Vintage' => [
                'images/Blazer Mid \'77 Vintage.png'
            ],
            'KD14' => [
                'images/KD14.png'
            ],
            'LeBron 19' => [
                'images/LeBron 19.png'
            ],
            'Metcon 7' => [
                'images/Metcon 7.png'
            ],
            'Mercurial Vapor 14' => [
                'images/Mercurial Vapor 14.png'
            ],
            'React Infinity Run Flyknit 2' => [
                'images/React Infinity Run Flyknit 2.png'
            ]
        ];
        
        foreach ($products as $product) {
            // Check if product name matches any of our Nike products
            foreach ($nikeImages as $productName => $images) {
                if (stripos($product->name, $productName) !== false) {
                    // Add images for this product
                    foreach ($images as $index => $imagePath) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePath,
                            'alt_text' => $product->name . ' image ' . ($index + 1),
                            'is_primary' => $index === 0,
                            'sort_order' => $index
                        ]);
                    }
                    break;
                }
            }
        }
    }
}