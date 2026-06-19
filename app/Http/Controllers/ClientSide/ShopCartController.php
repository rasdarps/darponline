<?php
namespace App\Http\Controllers\ClientSide;

use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
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
        $cartItems = Cart::content();
        $subtotal = Cart::subtotal();
        $total = Cart::total();
        $cartCount = Cart::count();
        $cartItems = [];
        $subtotal = 0;
        
        // foreach ($cart as $item) {
        //     $product = Product::find($item['product_id']);
        //     if ($product) {
        //         $item['product'] = $product;
        //         $item['total'] = $item['price'] * $item['quantity'];
        //         $cartItems[] = $item;
        //         $subtotal += $item['total'];
        //     }
        // }
        
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
        try {
            $validated = $request->validate([
                'product_id' => 'required|string',
                'quantity' => 'required|integer|min:1|max:999'
            ]);

            // Decrypt product ID
            $productId = Crypt::decrypt($validated['product_id']);
            $product = Product::findOrFail($productId);
            
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
            if ($product->quantity < $validated['quantity']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only ' . $product->quantity . ' units available in stock'
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
            
            // Check if product already exists in cart
            $existingCartItem = Cart::search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
            
            if ($existingCartItem->isNotEmpty()) {
                $rowId = $existingCartItem->first()->rowId;
                $currentQuantity = Cart::get($rowId)->qty;
                $newQuantity = $currentQuantity + $validated['quantity'];
                
                // Check if total quantity exceeds stock
                if ($product->quantity < $newQuantity) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Maximum reahed. We only have' . $product->quantity . ' units available in stock.'
                    ], 400);
                }
                
                // Update quantity
                Cart::update($rowId, $newQuantity);
            } else {
                // Add new product to cart
                Cart::add(
                    $product->id,
                    $product->name,
                    $validated['quantity'],
                    $product->selling_price ?? 0,
                    [
                        'product_slug' => $product->slug,
                        'sku' => $product->sku,
                        'image' => $product->getFirstMedia('featured_image')->getUrl('thumb'),
                        'max_quantity' => $product->quantity,
                        'weight' => $product->weight ?? 0,
                    ]
                );
            }
            
            // Remove coupon if cart changed
            Session::forget('coupon');
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to cart successfully',
                'cart_count' => Cart::count(),
                'cart_total' => $this->getCartTotalAfterDiscount(),
                'cart_subtotal' => Cart::total(),
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid product ID'
            ], 400);
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
                'product_id' => 'required|string',
                'quantity' => 'required|integer|min:1|max:999'
            ]);

            // Decrypt product ID
            $productId = Crypt::decrypt($validated['product_id']);
            $product = Product::findOrFail($productId);
            
            // Check if product exists in cart
            $existingCartItem = Cart::search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
            
            if ($existingCartItem->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            $rowId = $existingCartItem->first()->rowId;
            
            // Check if requested quantity exceeds stock
            if ($product->quantity < $validated['quantity']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only ' . $product->quantity . ' units available in stock'
                ], 400);
            }
            
            // Update quantity
            Cart::update($rowId, $validated['quantity']);
            
            // Get updated cart info
            $item = Cart::get($rowId);
            $itemTotal = $item->selling_price * $item->qty;
            $subtotal = Cart::total();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'item_total' => number_format($itemTotal, 2),
                'subtotal' => number_format($subtotal, 2),
                'cart_count' => Cart::count(),
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

            // Decrypt product ID
            $product = Product::findOrFail($validated['product_id']);
            
            // Find product in cart
            $existingCartItem = Cart::search(function ($cartItem, $rowId) use ($product) {
                return $cartItem->id === $product->id;
            });
            
            if ($existingCartItem->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found in cart'
                ], 404);
            }
            
            $rowId = $existingCartItem->first()->rowId;
            
            // Remove item from cart
            Cart::remove($rowId);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from cart',
                'cart_count' => Cart::count(),
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
            $subtotal = Cart::total();
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
            Cart::destroy();
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
        try {
            $count = Cart::count();
            
            return response()->json([
                'status' => 'success',
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart count error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get cart count'
            ], 500);
        }
    }
    
    /**
     * Get all cart items
     */
    public function getItems()
    {
        $items = Cart::content();
        $itemsArray = [];
        
        foreach ($items as $item) {
            $itemsArray[] = [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'options' => $item->options,
                'subtotal' => $item->price * $item->qty,
            ];
        }
        
        return response()->json([
            'status' => 'success',
            'items' => $itemsArray,
            'count' => Cart::count(),
            'total' => $this->getCartTotalAfterDiscount(),
            'subtotal' => Cart::total()
        ]);
    }
    

              /**
     * Get cart summary (all in one)
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary()
    {
        try {
            $items = Cart::content();
            $itemsArray = [];
            
            foreach ($items as $item) {
                $product = Product::find($item->id);
                $imageUrl = $product ? $product->featured_image_url : asset('images/default-product.jpg');
                
                $itemsArray[] = [
                    'rowId' => $item->rowId,
                    'id' => $item->id,
                    'name' => $item->name,
                    'qty' => $item->qty,
                    'price' => $item->selling_price,
                    'subtotal' => $item->selling_price * $item->qty,
                    'product_slug' => $item->options->product_slug ?? null,
                    'image' => $imageUrl,
                    'variant' => $item->options->variant ?? null,
                    'max_quantity' => $item->options->max_quantity ?? 999,
                ];
            }
            
            return response()->json([
                'status' => 'success',
                'items' => $itemsArray,
                'count' => Cart::count(),
                'subtotal' => Cart::total(),
                'total' => $this->getCartTotalAfterDiscount(),
                'has_coupon' => Session::has('coupon'),
                'coupon' => Session::get('coupon', null),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart summary error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get cart summary'
            ], 500);
        }
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
        $subtotal = Cart::total();
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
        /**
         * Get cart total (for AJAX)
         */
        public function getTotal()
        {
            return response()->json([
                'total' => $this->getCartTotalAfterDiscount()
            ]);
        }

                    /**
             * Get cart items for mini cart (for AJAX)
             */
           
}