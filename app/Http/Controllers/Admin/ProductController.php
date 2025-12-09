<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if SKU already exists (including soft deleted)
        $existingProduct = Product::withTrashed()->where('sku', $request->sku)->first();
        if ($existingProduct) {
            return redirect()->back()->withInput()->withErrors([
                'sku' => 'The SKU "' . $request->sku . '" has already been taken by product "' . $existingProduct->name . '"' . 
                         ($existingProduct->trashed() ? ' (soft deleted)' : '') . '. Please use a unique SKU.'
            ]);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,NULL,id,deleted_at,NULL',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'sku.unique' => 'The SKU has already been taken. Please use a unique SKU for this product.'
        ]);

        try {
            // Generate slug from name
            $slug = Str::slug($request->name);
            
            // Ensure slug is unique with a more robust approach
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
                // Safety check to prevent infinite loops
                if ($count > 1000) {
                    throw new \Exception('Unable to generate unique slug after 1000 attempts');
                }
            }

            $productData = $request->except('images');
            $productData['slug'] = $slug;
            
            // Log the slug that will be used
            \Log::info('Creating product with slug: ' . $slug);

            $product = Product::create($productData);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $uploadedImage) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $uploadedImage->getClientOriginalExtension();
                    
                    // Store original image
                    $path = $uploadedImage->storeAs('products', $filename, 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to create product: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category', 'images')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Generate slug from name if name is changing
            $productData = $request->except('images');
            if ($request->name !== $product->name) {
                $slug = Str::slug($request->name);
                
                // Ensure slug is unique with a more robust approach
                $originalSlug = $slug;
                $count = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                    // Safety check to prevent infinite loops
                    if ($count > 1000) {
                        throw new \Exception('Unable to generate unique slug after 1000 attempts');
                    }
                }
                
                $productData['slug'] = $slug;
            }

            $product->update($productData);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $uploadedImage) {
                    // Generate unique filename
                    $filename = time() . '_' . uniqid() . '.' . $uploadedImage->getClientOriginalExtension();
                    
                    // Store original image
                    $path = $uploadedImage->storeAs('products', $filename, 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0 && !$product->images()->exists(),
                        'sort_order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to update product: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}