<!-- Utilize Cart Menu Start -->
<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Cart</span>
            <button class="ltn__utilize-close">×</button>
        </div>
        
        <!-- Empty Cart Message -->
        <div class="mini-cart-product-area ltn__scrollbar cart-empty-title" id="miniCartEmpty" style="{{ Cart::count() > 0 ? 'display: none;' : 'display: block;' }}">
            <h5>Your cart is currently empty.</h5>
        </div>

        <!-- Cart Items -->
        <div class="mini-cart-product-area ltn__scrollbar cart-item-loop cart-item-display" id="miniCartItems" style="{{ Cart::count() > 0 ? 'display: block;' : 'display: none;' }}">
            @foreach(Cart::content() as $item)
                @php
                    // Get the product model from the cart item
                    $product = App\Models\Product::find($item->id);
                    // Get the featured image URL using Spatie Media Library
                    $imageUrl = $product->hasMedia('featured_image') ? $product->getFirstMedia('featured_image')->getUrl('thumb') : asset('client/shop/files/default_product_image.png');
                @endphp
                <div class="mini-cart-item clearfix" id="mini-cart-item-{{ $item->rowId }}">
                    <div class="mini-cart-img">
                        <a href="{{ route('shop.product.details', $item->options->product_slug ?? '#') }}">
                            <img src="{{ $imageUrl }}" alt="{{ $item->name }}">
                        </a>
                        <span onclick="removeFromCartMini('{{ $item->id }}', '{{ $item->rowId }}')" class="mini-cart-item-delete">
                            <i class="icon-cancel"></i>
                        </span>
                    </div>
                    <div class="mini-cart-info">
                        <h6>
                            <a href="{{ route('shop.product.details', $item->options->product_slug ?? '#') }}">
                                {{ $item->name }}
                                @if($item->options->variant ?? false)
                                    - {{ $item->options->variant }}
                                @endif
                            </a>
                        </h6>
                        <span class="mini-cart-quantity">
                            {{ $item->qty }} x GH₵{{ number_format($item->price , 2) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Footer -->
        <div class="mini-cart-footer cart-item-display" id="miniCartFooter" style="{{ Cart::count() > 0 ? 'display: block;' : 'display: none;' }}">
            <div class="mini-cart-sub-total">
                <h5>
                    Subtotal: 
                    <span class="shopping-cart__total" id="miniCartSubtotal">
                        GH₵{{ Cart::total() }}
                    </span>
                </h5>
            </div>
            <div class="btn-wrapper">
                <a href="{{ route('shop.cart.index') }}" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                {{-- <a href="{{ route('checkout.guest') }}" class="theme-btn-1 btn btn-effect-1">Checkout</a> --}}
            </div>
            <p>Free shipping on orders over <strong>$99</strong></p>
        </div>
    </div>
</div>
<!-- Utilize Cart Menu End -->