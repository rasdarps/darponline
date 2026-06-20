@extends('client_layout.main')
@push('styles')
    <style>
        /* Cart page quantity buttons */
        .cart-plus-minus-box-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background: #fff;
        }
        
        .cart-plus-minus .qtybutton {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 45px;
            background: #f8f9fa;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .cart-plus-minus .qtybutton:hover {
            background: #e9ecef;
        }
        
        .cart-plus-minus .cart-plus-minus-box {
            width: 50px;
            height: 45px;
            border: none;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            background: #fff;
        }
        
        .cart-plus-minus .cart-plus-minus-box:focus {
            outline: none;
        }
        
        .cart-plus-minus .cart-plus-minus-box::-webkit-outer-spin-button,
        .cart-plus-minus .cart-plus-minus-box::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .cart-plus-minus .cart-plus-minus-box[type=number] {
            -moz-appearance: textfield;
        }
        
        .cart-buttons .theme-default-button {
            display: inline-block;
            padding: 10px 25px;
            background: #071c1f;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }
        
        .cart-buttons .theme-default-button:hover {
            background: #f8796c;
            color: #fff;
            text-decoration: none;
        }
        
        .theme-default-button {
            display: inline-block;
            padding: 12px 30px;
            background: #071c1f;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-default-button:hover {
            background: #f8796c;
            color: #fff;
            text-decoration: none;
        }
        
        .proceed-to-checkout .theme-default-button {
            background: #f8796c;
            width: 100%;
            text-align: center;
        }
        
        .proceed-to-checkout .theme-default-button:hover {
            background: #071c1f;
        }
        
        .cart-total .table {
            margin-bottom: 0;
        }
        
        .cart-total .table td,
        .cart-total .table th {
            padding: 15px 0;
            border-top: none;
            border-bottom: 1px solid #eee;
        }
        
        .cart-total .table .order-total td,
        .cart-total .table .order-total th {
            border-bottom: none;
            padding-top: 20px;
        }
        
        .cart-total .table .order-total strong {
            font-size: 20px;
            color: #071c1f;
        }
        
        .cart-coupon textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 80px;
            resize: vertical;
        }
        
        .cart-coupon .input-group {
            display: flex;
            gap: 10px;
        }
        
        .cart-coupon .input-group input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .cart-coupon .input-group .theme-default-button {
            padding: 10px 20px;
            white-space: nowrap;
        }
        
        @media (max-width: 767px) {
            .cart-table table,
            .cart-table thead,
            .cart-table tbody,
            .cart-table th,
            .cart-table td,
            .cart-table tr {
                display: block;
            }
            
            .cart-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            
            .cart-table tr {
                border: 1px solid #dee2e6;
                margin-bottom: 15px;
            }
            
            .cart-table td {
                border: none;
                border-bottom: 1px solid #dee2e6;
                position: relative;
                padding-left: 50% !important;
            }
            
            .cart-table td:before {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                padding-right: 0;
                white-space: nowrap;
            }
            
            .cart-table table tbody tr td.pro-thumbnail {
                width: auto;
                max-width: 100%;
                min-width: 100%;
                text-align: center;
            }
            
            .cart-table table tbody tr td.pro-thumbnail a {
                display: block;
                min-width: unset;
                width: auto;
            }
            
            .cart-table table tbody tr td.pro-thumbnail a img {
                width: auto;
                max-width: 100%;
            }
            
            .cart-buttons {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .cart-buttons .theme-default-button {
                text-align: center;
                margin-right: 0;
            }
            
            .cart-coupon .input-group {
                flex-direction: column;
            }
            
            .proceed-to-checkout .theme-default-button {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endpush
@section('breadcrumb')
    <div id="shopify-section-breadcrumb" class="shopify-section">
        <!-- BREADCRUMB AREA START -->
        <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white plr--9 breadcrumbs overlay-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2">
                            <div class="section-title-area ltn__section-title-2">
                                <h1 class="breadcrumbs-title section-title white-color">Your Shopping Cart</h1>
                                <div class="ltn__breadcrumb-list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB AREA END -->
        <style>
            .breadcrumbs {
                background: #f2f4f5;
                padding-top: 50px;
                padding-bottom: 50px;
            }
            .overlay-bg::before {
                background: #071c1f none repeat scroll 0 0;
                opacity: 0;
            }
            .breadcrumbs-title {
                color: #071c1f;
            }
            .breadcrumb-list>li>a {
                color: #071c1f;
            }
            .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:after,
            .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:nth-child(2) a:after {
                background-color: #071c1f;
            }
            .breadcrumb-list>li,
            .ltn__breadcrumb-color-white .ltn__breadcrumb-list li:last-child {
                color: #f8796c;
            }
            .breadcrumbs .ltn__secondary-color {
                color: #f8796c !important;
            }
            .ltn__breadcrumb-inner-2 {
                justify-content: center;
                text-align: center;
            }
            @media (max-width: 767px) {
                .breadcrumbs {
                    padding-top: 15px;
                    padding-bottom: 15px;
                }
                .ltn__breadcrumb-inner-2 {
                    text-align: center;
                }
            }
        </style>
    </div>
@endsection

@section('content')
    <div id="shopify-section-template--14758548865142__main" class="shopify-section">
        <div class="cart-page theme-default-margin">
            <div class="container">
                @if(Cart::count() > 0)
                    <form id="cartForm" method="post" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-12">
                                <div class="cart-table table-responsive mb-40">
                                    <table>
                                        <tbody id="cartItemsContainer">
                                            @foreach($cartItemsWithProducts as $item)

                                            @php
                                                // Get the product model from the cart item
                                                $product = App\Models\Product::find(Illuminate\Support\Facades\Crypt::decrypt($item['id']));
                                                // Get the featured image URL using Spatie Media Library
                                                $imageUrl = $product->hasMedia('featured_image') ? $product->getFirstMedia('featured_image')->getUrl('thumb') : asset('client/shop/files/default_product_image.png');
                                            @endphp
                                                <tr id="cart-row-{{ $item['rowId'] }}">
                                                    <td class="pro-thumbnail">
                                                        <a href="{{ route('shop.product.details', $item['options']['product_slug'] ?? '#') }}">
                                                            <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}">

                                                        </a>
                                                    </td>
                                                    <td class="pro-title">
                                                        <a href="{{ route('shop.product.details', $item['options']['product_slug'] ?? '#') }}">
                                                            {{ $item['name'] }}
                                                        </a>
                                                        @if($item['options']['sku'] ?? false)
                                                            <br><small class="text-muted">SKU: {{ $item['options']['sku'] }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="pro-price">
                                                        <span class="amount">
                                                            <span class="money">GH₵{{ number_format($item['price'], 2) }}</span>
                                                        </span>
                                                    </td>
                                                    <td class="pro-quantity cart-product-quantity">
                                                        <div class="product-quantity cart-plus-minus quantity">
                                                            <div class="cart-plus-minus-box-wrapper">
                                                               
                                                                <input class="cart-plus-minus-box" 
                                                                    type="text" 
                                                                    value="{{ $item['qty'] }}" 
                                                                    name="updates[]" 
                                                                    id="qty-{{ $item['rowId'] }}"
                                                                    min="1" 
                                                                    max="{{ $item['options']['max_quantity'] ?? 999 }}"
                                                                    data-rowid="{{ $item['rowId'] }}"
                                                                    data-productid="{{ $item['id'] }}">
                                                                
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="pro-subtotal" id="subtotal-{{ $item['rowId'] }}">
                                                        <span class="money">GH₵{{ number_format($item['subtotal'], 2) }}</span>
                                                    </td>
                                                    <td class="pro-remove">
                                                        <a href="javascript:void(0);" onclick="removeFromCart('{{ $item['id'] }}')">×</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="cart-buttons">
                                    <a class="theme-default-button" href="{{ route('shop.products') }}">
                                        <i class="fas fa-arrow-left"></i> Continue Shopping
                                    </a>
                                    <a class="theme-default-button" href="javascript:void(0);" onclick="clearCart()">
                                        <i class="fas fa-trash"></i> Clear Cart
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-12 col-12">
                                <div class="cart-payment">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="cart-coupon">
                                                <h3>Special instructions for seller</h3>
                                                <textarea name="note" class="" id="CartSpecialInstructions" placeholder="Any special instructions..."></textarea>
                                            </div>
                                            
                                            <!-- Coupon Code Section -->
                                            <div class="cart-coupon mt-4">
                                                <h3>Coupon Code</h3>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="coupon_code" placeholder="Enter coupon code">
                                                    <div class="input-group-append">
                                                        <button class="theme-default-button" type="button" onclick="applyCoupon()">
                                                            Apply Coupon
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="couponMessage" class="mt-2"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="cart-total">
                                                <h3>Cart Totals</h3>
                                                <table class="table">
                                                    <tbody>
                                                        <tr class="cart-subtotal">
                                                            <th>Subtotal</th>
                                                            <td>
                                                                <span class="amount">
                                                                    <span id="cart-subtotal">
                                                                        <span class="money">GH₵{{ number_format($subtotal, 2) }}</span>
                                                                    </span>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @if(Session::has('coupon'))
                                                            <tr class="cart-discount">
                                                                <th>Discount ({{ Session::get('coupon.code') }})</th>
                                                                <td>
                                                                    <span class="amount text-success">
                                                                        -GH₵<span id="cart-discount">{{ number_format($discount, 2) }}</span>
                                                                    </span>
                                                                    <button type="button" class="btn btn-sm btn-danger ml-2" onclick="removeCoupon()">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr class="order-total">
                                                            <th>Total</th>
                                                            <td>
                                                                <strong>
                                                                    <span class="amount">
                                                                        <span id="cart-total">
                                                                            <span class="money">GH₵{{ number_format($total, 2) }}</span>
                                                                        </span>
                                                                    </span>
                                                                </strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                {{-- <div class="proceed-to-checkout">
                                                    <a href="{{ route('checkout.guest') }}" class="theme-default-button">
                                                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="row">
                        <div class="col-12 text-center py-5">
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                                <h3>Your cart is empty</h3>
                                <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                                <a href="{{ route('shop.products') }}" class="theme-default-button mt-3">
                                    <i class="fas fa-arrow-left"></i> Start Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="shopify-section-template--14758548865142__474a1338-1821-4778-a604-18dfa0ef27ea" class="shopify-section">
        <style data-shopify="">
            #section-template--14758548865142__474a1338-1821-4778-a604-18dfa0ef27ea {
                padding-top: 0px;
                padding-bottom: 60px;
            }
    
            @media (min-width: 768px) and (max-width: 991px) {
                #section-template--14758548865142__474a1338-1821-4778-a604-18dfa0ef27ea {
                    padding-top: 0px;
                    padding-bottom: 40px;
                }
            }
    
            @media (max-width: 767px) {
                #section-template--14758548865142__474a1338-1821-4778-a604-18dfa0ef27ea {
                    padding-top: 0px;
                    padding-bottom: 30px;
                }
            }
        </style><!-- PRODUCT SLIDER AREA START -->
        <div class="ltn__product-slider-area ltn__product-gutter"  id="section-template--14758548865142__474a1338-1821-4778-a604-18dfa0ef27ea" data-section="featuredCollection">

            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title-area text-center">
                            <h2 class="section-title">Featured Products</h2>
                            <p>You might also like these products</p>
                        </div>
                    </div>
                </div>
                <div class="row ltn__product-slider-item-four-active slick-arrow-1" data-slick='{"rows": 1,
                "slidesToShow": 4,
                 "responsive":[
                     {"breakpoint":992, "settings": {"slidesToShow": 3, "arrows": false, "dots": true} },
                     {"breakpoint":768, "settings": {"slidesToShow": 2, "arrows": false, "dots": true} },
                     {"breakpoint":480, "settings": {"slidesToShow": 2, "slidesToScroll": 2, "arrows": false, "dots": true} }
                   ] 
 
                    }'>
                    @foreach($featuredProducts ?? [] as $product)
                        <div class="col-lg-12">
                            <div class="ltn__product-item ltn__product-item-3 text-center product-wrapper">
                                <div class="product-img">
                                    <a href="{{ route('shop.product.details', $product->slug) }}" 
                                    class="image-position-wrapper primary_img" 
                                    style="padding-bottom: 100.0%;">

                                    @if($product->hasMedia('featured_image'));

                                            {{ $product->getFirstMedia('featured_image')->img('uniform')->attributes([
                                                'class' => 'image-position-size popup_cart_image',
                                                'width' => '600',
                                                'height' => '600',
                                                'alt' => $product->name,
                                                'loading' => 'lazy',
                                                ]) 
                                            }}
                                    @else
                                        <img class="image-position-size popup_cart_image" width="600" height="600" alt="{{$product->name}}" loading="lazy"sizes="18vw"
                                        src="{{asset('client/shop/files/default_product_image.png')}}">
                                    @endif
                                        
                                    </a>
                                    @if($product->hasMedia('gallery_images'))
                                        <a href="{{ route('shop.product.details', $product->slug) }}" 
                                        class="image-position-wrapper secondary_img" 
                                        style="padding-bottom: 100.0%;">

                                        {!! $product->getMedia('gallery_images')->first()->img('uniform')->attributes([
                                                    'class' => 'image-position-size popup_cart_image',
                                                    'width' => '600',
                                                    'height' => '600',
                                                    'alt' => $product->name,
                                                    'loading' => 'lazy',
                                                ]) !!}
                                            
                                        </a>
                                    @endif
                                    <div class="product-badge">
                                        <ul>
                                            @if($product->is_new)
                                                <li class="sale-badge">New</li>
                                            @endif
                                            @if($product->is_featured)
                                                <li class="sale-badge" style="background: #ff8c00;">Featured</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-title">
                                        <a href="{{ route('shop.product.details', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-price">
                                        <span>GH₵{{ number_format($product->price ?? 0, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Update cart quantity
        function updateCartQuantity(rowId, change, productId) {
            var input = $('#qty-' + rowId);
            var currentVal = parseInt(input.val()) || 1;
            var maxVal = parseInt(input.attr('max')) || 999;
            var newVal = currentVal + change;
            
            if (newVal < 1) {
                Notiflix.Notify.warning('Minimum quantity is 1');
                return;
            }
            
            if (newVal > maxVal) {
                Notiflix.Notify.warning('Only ' + maxVal + ' items available');
                return;
            }
            
            input.val(newVal);
            
            // Update cart via AJAX
            Notiflix.Loading.pulse('Updating cart...');
            
            $.ajax({
                url: '{{ route("shop.cart.update") }}',
                type: 'PUT',
                data: {
                    product_id: productId,
                    quantity: newVal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        // Update item subtotal
                        $('#subtotal-' + rowId + ' .money').text('$' + response.item_total);
                        // Update cart subtotal
                        $('#cart-subtotal .money').text('$' + response.subtotal);
                        // Update total if coupon applied
                        if (response.cart_total) {
                            $('#cart-total .money').text('$' + response.cart_total);
                        }
                        // Update cart count in header
                        updateCartCount();
                        Notiflix.Notify.success('Cart updated successfully');
                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to update cart');
                    }
                },
                error: function(xhr) {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure('Failed to update cart');
                }
            });
        }
        
        // Remove from cart
        function removeFromCart(productId) {
            Notiflix.Confirm.show(
                'Remove Item',
                'Are you sure you want to remove this item from cart?',
                'Yes',
                'Cancel',
                function() {
                    Notiflix.Loading.pulse('Removing item...');
                    
                    $.ajax({
                        url: '{{ route("shop.cart.remove") }}',
                        type: 'DELETE',
                        data: {
                            product_id: productId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Notiflix.Loading.remove();
                            if (response.status === 'success') {
                                Notiflix.Notify.success('Item removed from cart');
                                location.reload();
                            } else {
                                Notiflix.Notify.warning(response.message || 'Failed to remove item');
                            }
                        },
                        error: function() {
                            Notiflix.Loading.remove();
                            Notiflix.Notify.failure('An error occurred');
                        }
                    });
                }
            );
        }
        
        // Clear cart
        function clearCart() {
            Notiflix.Confirm.show(
                'Clear Cart',
                'Are you sure you want to clear your entire cart?',
                'Yes',
                'Cancel',
                function() {
                    Notiflix.Loading.pulse('Clearing cart...');
                    
                    $.ajax({
                        url: '{{ route("shop.cart.clear") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Notiflix.Loading.remove();
                            if (response.status === 'success') {
                                Notiflix.Notify.success('Cart cleared');
                                location.reload();
                            } else {
                                Notiflix.Notify.warning(response.message || 'Failed to clear cart');
                            }
                        },
                        error: function() {
                            Notiflix.Loading.remove();
                            Notiflix.Notify.failure('An error occurred');
                        }
                    });
                }
            );
        }
        
        // Apply coupon
        function applyCoupon() {
            var couponCode = $('#coupon_code').val();
            
            if (!couponCode) {
                Notiflix.Notify.warning('Please enter a coupon code');
                return;
            }
            
            Notiflix.Loading.pulse('Applying coupon...');
            
            $.ajax({
                url: '{{ route("shop.cart.coupon.apply") }}',
                type: 'POST',
                data: {
                    code: couponCode,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        Notiflix.Notify.success('Coupon applied successfully!');
                        location.reload();
                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to apply coupon');
                    }
                },
                error: function(xhr) {
                    Notiflix.Loading.remove();
                    if (xhr.status === 404) {
                        Notiflix.Notify.warning(xhr.responseJSON?.message || 'Invalid coupon code');
                    } else {
                        Notiflix.Notify.failure('An error occurred');
                    }
                }
            });
        }
        
        // Remove coupon
        function removeCoupon() {
            Notiflix.Confirm.show(
                'Remove Coupon',
                'Are you sure you want to remove this coupon?',
                'Yes',
                'Cancel',
                function() {
                    Notiflix.Loading.pulse('Removing coupon...');
                    
                    $.ajax({
                        url: '{{ route("shop.cart.coupon.remove") }}',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Notiflix.Loading.remove();
                            if (response.status === 'success') {
                                Notiflix.Notify.success('Coupon removed');
                                location.reload();
                            } else {
                                Notiflix.Notify.warning(response.message || 'Failed to remove coupon');
                            }
                        },
                        error: function() {
                            Notiflix.Loading.remove();
                            Notiflix.Notify.failure('An error occurred');
                        }
                    });
                }
            );
        }
        
        // Update cart count in header
        function updateCartCount() {
            $.ajax({
                url: '{{ route("shop.cart.count") }}',
                type: 'GET',
                success: function(response) {
                    if (response.count !== undefined) {

                        
                        document.querySelectorAll('[data-cart="count"]').forEach(element => {
                            element.textContent = response.count;
                        });
                    
                    }
                }
            });
        }
        
        // Manual quantity input change
        $(document).on('change', '.cart-plus-minus-box', function() {
            var input = $(this);
            var rowId = input.data('rowid');
            var productId = input.data('productid');
            var newVal = parseInt(input.val()) || 1;
            var maxVal = parseInt(input.attr('max')) || 999;
            var minVal = parseInt(input.attr('min')) || 1;
            
            console.log(newVal);
            
            // Validate and correct value
            if (newVal < minVal) {
                input.val(minVal);
                newVal = minVal;
                Notiflix.Notify.warning('Minimum quantity is 1');

                
            } else if (newVal > maxVal) {

                
                input.val(maxVal);
                newVal = maxVal;
                Notiflix.Notify.warning('Only ' + maxVal + ' items available');
            }
            
            // Update cart
            // Notiflix.Loading.pulse('Updating cart...');
            
            $.ajax({
                url: '{{ route("shop.cart.update") }}',
                type: 'PUT',
                data: {
                    product_id: productId,
                    quantity: newVal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        $('#subtotal-' + rowId + ' .money').text('GH₵' + response.item_total);
                        $('#cart-subtotal .money').text('GH₵' + response.subtotal);
                        if (response.cart_total) {
                            $('#cart-total .money').text('GH₵' + response.cart_total);
                        }
                        updateCartCount();
                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to update cart');
                    }
                },
                error: function() {
                    // Notiflix.Loading.remove();
                    Notiflix.Notify.failure('Failed to update cart');
                }
            });
        });
    </script>
@endpush