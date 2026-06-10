<?php

namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopCategoryController extends Controller
{
    //

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        // Get products in this category
        $products = Product::where('shop_category_id', $category->id)
            ->where('is_online', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(24);
            
        // Get all active categories for sidebar
        $allCategories = Category::active()
            ->ordered()
            ->get();
            
        // Get featured products in this category
        $featuredProducts = Product::where('shop_category_id', $category->id)
            ->where('is_online', true)
            ->where('is_featured', true)
            ->limit(8)
            ->get();
            
        return view('shop.categories.show', compact(
            'category', 'products', 'allCategories', 'featuredProducts'
        ));
    }

    public function getAllCategories()
    {
        $categories = Category::active()
        ->homepage()
        ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
}
