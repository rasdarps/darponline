<?php

namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OnlineShoppingController extends Controller
{
    //

    public function index(){

        
        $products = Product::with(['category', 'brand', 'unit'])
        ->where('is_online', true)
        ->where('is_active', true)
        ->orderBy('is_featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get();

         // Get new arrivals
         $newArrivals = Product::with(['category', 'brand', 'unit'])
         ->where('is_online', true)
         ->where('is_active', true)
         ->where('is_new', true)
         ->orderBy('created_at', 'desc')
         ->take(10)
         ->get();
     
        // Get featured products specifically
        $featuredProducts = Product::with(['category', 'brand', 'unit'])
         ->where('is_online', true)
         ->where('is_active', true)
         ->where('is_featured', true)
         ->orderBy('created_at', 'desc')
         ->take(10)
         ->get();

        $collections = Category::active()->homepage()->get();
        return view('client.index', compact('collections', 'products', 'newArrivals', 'featuredProducts'));
    }

    public function collection_page(){
        return view('client.collection_page');
    }

    public function product_page(){
        return view('client.product_page');
    }

    public function productDetails($slug)
    {
        $product = Product::with(['category', 'brand', 'unit'])
            ->where('slug', $slug)
            ->where('is_online', true)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Get gallery images from Spatie Media Library
        $galleryImages = $product->getMedia('gallery_images');
        
        // Get featured image
        $featuredImage = $product->getFirstMedia('featured_image')->img('uniform');
        
        // Get related products (same category)
        $relatedProducts = Product::with(['category', 'brand', 'unit'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_online', true)
            ->where('is_active', true)
            ->take(10)
            ->get();

            // Calculate visitor count (random for demo)
        $visitorCount = rand(5, 20);
        
        // Calculate estimated delivery date
        $deliveryStart = now()->addDays(rand(2, 5));
        $deliveryEnd = now()->addDays(rand(6, 10));
        
        
        // Get product colors/variants (if you have variants table)
        // For now, using color from product model
        
        return view('client.product_details', compact(
            'product',
            'galleryImages',
            'featuredImage',
            'relatedProducts',
            'visitorCount',
            'deliveryStart',
            'deliveryEnd'
        ));
    }

    public function quickView($slug)
    {
        try {
            $product = Product::with(['category', 'brand', 'unit'])
                ->where('slug', $slug)
                ->where('is_online', true)
                ->where('is_active', true)
                ->firstOrFail();
            
            // Prepare product data for response
            $productData = [
                'id' => Crypt::encrypt($product->id),
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'description' => $product->description,
                'price' => $product->price ?? 0,
                'sale_price' => $product->sale_price ?? null,
                'quantity' => $product->quantity,
                'stock_level' => $product->stock_level,
                'featured_image_url' => $product->getFirstMedia('featured_image')->getUrl('uniform'),
                'category' => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name] : null,
                'brand' => $product->brand ? ['id' => $product->brand->id, 'name' => $product->brand->name] : null,
                'unit' => $product->unit ? ['id' => $product->unit->id, 'name' => $product->unit->name] : null,
                'color' => $product->color ? ['id' => $product->color->id, 'name' => $product->color->name] : null,
                'is_in_stock' => $product->isInStock(),
                'is_low_stock' => $product->isLowStock(),
                'is_out_of_stock' => $product->isOutOfStock(),
            ];
            
            return response()->json([
                'status' => 'success',
                'product' => $productData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Quick view error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function wishlist_page() 
    {
        return view('client.wishlist_page');

    }
}
