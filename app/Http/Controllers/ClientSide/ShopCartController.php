<?php

namespace App\Http\Controllers\ClientSide;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ShopCartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $item['product'] = $product;
                $item['total'] = $item['price'] * $item['quantity'];
                $cartItems[] = $item;
                $subtotal += $item['total'];
            }
        }
        
        $coupon = Session::get('coupon', null);
        $discount = 0;
        $total = $subtotal;
        
        if ($coupon) {
            $discount = $this->calculateDiscount($subtotal, $coupon);
            $total = $subtotal - $discount;
        }
        
        return view('shop.cart', compact('cartItems', 'subtotal', 'discount', 'total', 'coupon'));
    }
    
    /**
     * Add product to cart
     */
    public function add(Request $request)
    {

        $id = Crypt::decrypt($request->id);
        try {
            $validated = $request->validate([
                // 'product_id' => 'required|exists:products,'.$id.'',
                'quantity' => 'required|integer|min:1|max:999'
            ]);


            
            $product = Product::findOrFail(Crypt::decrypt($request->product_id));
            
            // Check if product is available
            if (!$product->is_online || !$product->is_active) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is not available'
                ], 400);
            }
            
            // Check stock availability
            if ($product->isOutOfStock()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product is out of stock'
                ], 400);
            }
            
            // Check if requested quantity is available
            if ($product->stock_quantity < $validated['quantity']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only ' . $product->stock_quantity . ' units available in stock'
                ], 400);
            }
            
            // Check minimum and maximum order quantities
            if ($product->min_order_quantity && $validated['quantity'] < $product->min_order_quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Minimum order quantity is ' . $product->min_order_quantity
                ], 400);
            }
            
            if ($product->max_order_quantity && $validated['quantity'] > $product->max_order_quantity) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maximum order quantity is ' . $product->max_order_quantity
                ], 400);
            }
            
            $cart = Session::get('cart', []);
            
            // Check if product already exists in cart
            $existingIndex = $this->findProductInCart($cart, $validated['product_id']);
            
            if ($existingIndex !== false) {
                $newQuantity = $cart[$existingIndex]['quantity'] + $validated['quantity'];
                
                // Check if total quantity exceeds stock
                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Only ' . $product->stock_quantity . ' units available in stock'
                    ], 400);
                }
                
                $cart[$existingIndex]['quantity'] = $newQuantity;
            } else {
                // Add new product to cart
                $cart[] = [
                    'product_id' => $product->id,
                    'product_slug' => $product->slug,
                    'name' => $product->name,
                    'price' => $product->price ?? 0,
                    'quantity' => $validated['quantity'],
                    'image' => $product->featured_image_url,
                    'sku' => $product->sku,
                    'max_quantity' => $product->stock_quantity,
                ];
            }
            
            Session::put('cart', $cart);
            Session::forget('coupon');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart successfully',
                'cart_count' => $this->getCartCount(),
                'cart_total' => number_format($this->getCartTotalAfterDiscount(), 2)
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add product to cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:999'
            ]);
            
            $cart = Session::get('cart', []);
            $index = $this->findProductInCart($cart, $validated['product_id']);
            
            if ($index === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            // Update quantity
            $cart[$index]['quantity'] = $validated['quantity'];
            Session::put('cart', $cart);
            
            // Recalculate totals
            $itemTotal = $cart[$index]['price'] * $cart[$index]['quantity'];
            $subtotal = $this->getCartTotal();
            $cartCount = $this->getCartCount();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'item_total' => number_format($itemTotal, 2),
                'subtotal' => number_format($subtotal, 2),
                'cart_count' => $cartCount,
                'cart_total' => number_format($this->getCartTotalAfterDiscount(), 2)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id'
            ]);
            
            $cart = Session::get('cart', []);
            $index = $this->findProductInCart($cart, $validated['product_id']);
            
            if ($index === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            // Remove item
            unset($cart[$index]);
            $cart = array_values($cart); // Reindex array
            
            Session::put('cart', $cart);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from cart',
                'cart_count' => $this->getCartCount(),
                'cart_total' => number_format($this->getCartTotalAfterDiscount(), 2)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove product: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50'
            ]);
            
            // Find coupon
            $coupon = Coupon::where('code', $validated['code'])
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                })
                ->first();
            
            if (!$coupon) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired coupon code'
                ], 404);
            }
            
            // Check minimum order amount
            $subtotal = $this->getCartTotal();
            if ($coupon->minimum_amount && $subtotal < $coupon->minimum_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Minimum order amount of $" . number_format($coupon->minimum_amount, 2) . " required"
                ], 400);
            }
            
            // Store coupon in session
            Session::put('coupon', [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'minimum_amount' => $coupon->minimum_amount
            ]);
            
            $discount = $this->calculateDiscount($subtotal, $coupon);
            $total = $subtotal - $discount;
            
            return response()->json([
                'status' => 'success',
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'code' => $coupon->code,
                    'discount' => number_format($discount, 2),
                    'total' => number_format($total, 2)
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to apply coupon: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove coupon from cart
     */
    public function removeCoupon()
    {
        try {
            Session::forget('coupon');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Coupon removed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to remove coupon: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            Session::forget('cart');
            Session::forget('coupon');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart cleared successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to clear cart: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get cart item count
     */
    public function count()
    {
        return response()->json([
            'count' => $this->getCartCount()
        ]);
    }
    
    /**
     * Get all cart items
     */
    public function getItems()
    {
        $cart = Session::get('cart', []);
        $items = [];
        
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $item['product'] = $product;
                $items[] = $item;
            }
        }
        
        return response()->json([
            'status' => 'success',
            'items' => $items,
            'count' => $this->getCartCount(),
            'total' => number_format($this->getCartTotalAfterDiscount(), 2)
        ]);
    }
    
    /**
     * Helper: Find product in cart by ID
     */
    private function findProductInCart($cart, $productId)
    {
        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $productId) {
                return $index;
            }
        }
        return false;
    }
    
    /**
     * Helper: Get total cart count
     */
    private function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
    
    /**
     * Helper: Get cart subtotal
     */
    private function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
    
    /**
     * Helper: Get cart total after discount
     */
    private function getCartTotalAfterDiscount()
    {
        $subtotal = $this->getCartTotal();
        $coupon = Session::get('coupon', null);
        
        if ($coupon) {
            $discount = $this->calculateDiscount($subtotal, $coupon);
            return $subtotal - $discount;
        }
        
        return $subtotal;
    }
    
    /**
     * Helper: Calculate discount based on coupon type
     */
    private function calculateDiscount($subtotal, $coupon)
    {
        if (is_array($coupon)) {
            $coupon = (object) $coupon;
        }
        
        if ($coupon->type === 'percentage') {
            return ($subtotal * $coupon->value) / 100;
        } elseif ($coupon->type === 'fixed') {
            return $coupon->value;
        }
        
        return 0;
    }
}