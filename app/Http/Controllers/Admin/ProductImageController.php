<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Delete the image file from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the image record from database
        $image->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Set the specified image as primary.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function setPrimary($id)
    {
        $image = ProductImage::findOrFail($id);
        
        // Set all images of this product as non-primary
        ProductImage::where('product_id', $image->product_id)->update(['is_primary' => false]);
        
        // Set this image as primary
        $image->update(['is_primary' => true]);
        
        return response()->json(['success' => true]);
    }
}
