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
        $cartItemsWithProducts = [];
        $subtotal = 0;
        
        // Fetch fresh product data for each cart item
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            
            if ($product) {
                // Update the cart item with fresh product data
                $cartItemsWithProducts[] = [
                    'rowId' => $item->rowId,
                    'id' => Crypt::encrypt($product->id),
                    'name' => $product->name,
                    'price' => $product->selling_price ?? 0,
                    'qty' => $item->qty,
                    'subtotal' => ($product->selling_price ?? 0) * $item->qty,
                    'product' => $product,
                    'options' => $item->options,
                ];
                
                $subtotal += ($product->selling_price ?? 0) * $item->qty;
            }
        }
        $featuredProducts = Product::with(['category', 'brand', 'unit'])
        ->where('is_online', true)
        ->where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();

        $total = $subtotal;
        $cartCount = Cart::count();
        
        // Get coupon from session if exists
        $coupon = Session::get('coupon', null);
        $discount = 0;
        
        if ($coupon) {
            $discount = $this->calculateDiscount($subtotal, $coupon);
            $total = $subtotal - $discount;
        }

        
        return view('client.cart_page', compact('cartItemsWithProducts','featuredProducts', 'subtotal', 'discount', 'total', 'coupon', 'cartCount'));
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
                        'max_quantity' => $product->quantity,
                    ]
                );
            }
            
            // Remove coupon if cart changed
            Session::forget('coupon');
            
            // Get fresh totals from database
            $subtotal = $this->getCartSubtotalFromDB();
            $total = $subtotal;
            $discount = 0;

            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                $discount = $this->calculateDiscount($subtotal, $coupon);
                $total = $subtotal - $discount;
            }
                        
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
            
            // Get fresh totals from database
            $itemTotal = ($product->selling_price ?? 0) * $validated['quantity'];
            $subtotal = $this->getCartSubtotalFromDB();
            $total = $subtotal;
            $discount = 0;

            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                $discount = $this->calculateDiscount($subtotal, $coupon);
                $total = $subtotal - $discount;
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Cart updated successfully',
                'item_total' => number_format($itemTotal, 2),
                'subtotal' => number_format($subtotal, 2),
                'discount' => number_format($discount, 2),
                'cart_total' => number_format($total, 2),
                'cart_count' => Cart::count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cart subtotal from database (fresh prices)
     */
    private function getCartSubtotalFromDB()
    {
        $subtotal = 0;
        $cartItems = Cart::content();
        
        foreach ($cartItems as $item) {
            $product = Product::find($item->id);
            if ($product) {
                $subtotal += ($product->selling_price ?? 0) * $item->qty;
            }
        }
        
        return $subtotal;
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {

        try {
            $validated = $request->validate([
                'product_id' => 'required|string',
            ]);

             // Decrypt product ID
             $productId = Crypt::decrypt($validated['product_id']);
             $product = Product::findOrFail($productId);
            
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
        try {
            $cartItems = Cart::content();
            $itemsArray = [];
            $subtotal = 0;
            
            foreach ($cartItems as $item) {
                $product = Product::find($item->id);
                
                if ($product) {

                    $imageUrl = $product->hasMedia('featured_image') ? $product->getFirstMedia('featured_image')->getUrl('thumb') : asset('client/shop/files/default_product_image.png');

                    $price = $product->selling_price ?? 0;
                    $subtotal += $price * $item->qty;
                    
                    $itemsArray[] = [
                        'rowId' => $item->rowId,
                        'id' => $product->id,
                        'name' => $product->name,
                        'qty' => $item->qty,
                        'price' => number_format($price, 2),
                        'subtotal' => number_format($price * $item->qty, 2),
                        'product_slug' => $product->slug,
                        'sku' => $product->sku,
                        'image' => $imageUrl,
                        'stock_quantity' => $product->stock_quantity,
                        'max_quantity' => $product->stock_quantity,
                    ];
                }
            }
            
            $discount = 0;
            $total = $subtotal;
            
            if (Session::has('coupon')) {
                $coupon = Session::get('coupon');
                $discount = $this->calculateDiscount($subtotal, $coupon);
                $total = $subtotal - $discount;
            }
            
            return response()->json([
                'status' => 'success',
                'items' => $itemsArray,
                'count' => Cart::count(),
                'subtotal' => number_format($subtotal, 2),
                'discount' => number_format($discount, 2),
                'total' => number_format($total, 2),
                'has_coupon' => Session::has('coupon'),
                'coupon' => Session::get('coupon', null),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Cart items error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get cart items'
            ], 500);
        }
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
                $imageUrl = $product->hasMedia('featured_image') ? $product->getFirstMedia('featured_image')->getUrl('thumb') : asset('client/shop/files/default_product_image.png');
                
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
        $subtotal = $this->getCartSubtotalFromDB();
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