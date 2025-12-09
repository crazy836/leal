<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as ImageFacade;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ProductImage::with(['product', 'product.category']);
        
        // Apply filters if provided
        if ($request->has('product') && $request->product != '') {
            $query->where('product_id', $request->product);
        }
        
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('product.category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $images = $query->latest()->paginate(12);
        $products = Product::all();
        $categories = Category::all();
        
        return view('admin.images.index', compact('images', 'products', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $images = $request->file('images');
        
        // Limit to 5 images per upload
        if (count($images) > 5) {
            return response()->json([
                'success' => false,
                'message' => 'You can only upload up to 5 images at a time.'
            ]);
        }
        
        $uploadedImages = [];
        $setPrimary = $request->has('set_primary');
        
        foreach ($images as $index => $imageFile) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $path = 'products/' . $filename;
            
            // Store original image
            $imageFile->storeAs('public/products', $filename);
            
            // Create thumbnail
            $thumbnailPath = 'products/thumbnails/' . $filename;
            $thumbnail = ImageFacade::make($imageFile)->fit(300, 300);
            Storage::disk('public')->put($thumbnailPath, $thumbnail->encode());
            
            // Determine if this should be the primary image
            $isPrimary = $setPrimary && $index === 0;
            
            // If setting as primary, unset any existing primary image for this product
            if ($isPrimary) {
                ProductImage::where('product_id', $product->id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
            
            // Create product image record
            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'thumbnail_path' => $thumbnailPath,
                'is_primary' => $isPrimary,
            ]);
            
            $uploadedImages[] = $productImage;
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully!',
            'images' => $uploadedImages
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Delete image files from storage
        Storage::disk('public')->delete([
            $image->image_path,
            $image->thumbnail_path
        ]);
        
        // Delete the database record
        $image->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!'
        ]);
    }
    
    /**
     * Set the specified image as primary for its product.
     */
    public function setPrimary(string $id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Unset any existing primary image for this product
        ProductImage::where('product_id', $image->product_id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->is_primary = true;
        $image->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Primary image updated successfully!'
        ]);
    }
}