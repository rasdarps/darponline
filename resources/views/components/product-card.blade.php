@props([
    'product',
    'columns' => 3,
    'showColorSwatches' => true,
    'showCompare' => true,
    'showWishlist' => true,
    'showQuickView' => true,
    'showCart' => true
])

@php
    $colClass = match($columns) {
        2 => 'col-lg-6 col-md-6',
        3 => 'col-lg-4 col-md-6',
        4 => 'col-lg-3 col-md-6',
        default => 'col-lg-4 col-md-6'
    };
    
    $featuredImage = $product->featured_image_url ?? asset('images/default-product.jpg');
    $galleryImages = $product->gallery_images ?? [];
    $hasGallery = count($galleryImages) > 0;
    $discount = $product->sale_price && $product->price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0;
    $isOnSale = $discount > 0;
    $colors = $product->colors ?? [];
@endphp

<div class="{{ $colClass }}">
    <div class="ltn__product-item ltn__product-item-3 text-center product-wrapper">
        <div class="product-img">
            <a href="{{ route('product.details', $product->slug) }}" 
               class="image-position-wrapper primary_img" 
               style="padding-bottom: 100.0%;">
                <img 
                    src="{{ $featuredImage }}" 
                    alt="{{ $product->name }}" 
                    loading="lazy" 
                    class="image-position-size popup_cart_image"
                    width="600" 
                    height="600"
                >
            </a>
            
            @if($hasGallery)
                <a href="{{ route('product.details', $product->slug) }}" 
                   class="image-position-wrapper secondary_img" 
                   style="padding-bottom: 100.0%;">
                    <img 
                        src="{{ $galleryImages[0]['medium'] ?? $galleryImages[0]['original'] ?? $galleryImages[0] }}" 
                        alt="{{ $product->name }}" 
                        loading="lazy" 
                        class="image-position-size popup_cart_image"
                        width="600" 
                        height="600"
                    >
                </a>
            @endif
            
            <div class="product-badge">
                <ul>
                    @if($isOnSale)
                        <li class="sale-badge">-{{ $discount }}%</li>
                    @endif
                    @if($product->is_new)
                        <li class="new-badge">New</li>
                    @endif
                    @if($product->is_featured)
                        <li class="featured-badge">Featured</li>
                    @endif
                </ul>
            </div>
            
            <div class="product-hover-action">
                <ul>
                    @if($showQuickView)
                        <li>
                            <a title="Quickview" 
                               data-toggle="modal" 
                               data-target="#quickViewModal"
                               href="javascript:void(0);" 
                               onclick="quickView('{{ $product->slug }}')">
                                <i class="far fa-eye"></i>
                            </a>
                        </li>
                    @endif
                    
                    @if($showCart && $product->is_online)
                        <li>
                            <a href="javascript:void(0);" 
                               onclick="addToCart({{ $product->id }}, 1, '{{ $product->slug }}')" 
                               class="" 
                               title="Add to cart">
                                <span>
                                    <span class="cart-title"><i class="fas fa-shopping-cart"></i></span>
                                </span>
                            </a>
                        </li>
                    @endif
                    
                    @if($showWishlist)
                        <li>
                            <a class="action-wishlist tile-actions--btn flex wishlist-btn wishlist"
                               href="javascript: void(0)" 
                               button-wishlist
                               data-product-handle="{{ $product->slug }}" 
                               title="Add to wishlist"
                               onclick="toggleWishlist({{ $product->id }}, '{{ $product->slug }}')">
                                <span class="add-wishlist"><i class="far fa-heart"></i></span>
                                <span class="loading-wishlist"><i class="fas fa-spinner fa-pulse"></i></span>
                                <span class="remove-wishlist"><i class="fas fa-heart"></i></span>
                            </a>
                        </li>
                    @endif
                    
                    @if($showCompare)
                        <li>
                            <a title="Add to compare" 
                               data-toggle="modal" 
                               href="javascript:void(0);" 
                               class="compare" 
                               data-pid="{{ $product->slug }}"
                               onclick="addToCompare({{ $product->id }}, '{{ $product->slug }}')">
                                <i class="fas fa-exchange-alt"></i><span> Compare</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            
            @if($isOnSale && $product->sale_end_date)
                <div class="product-grid-countdown" data-countdown="{{ $product->sale_end_date }}"></div>
            @endif
        </div>
        
        <div class="product-info">
            <h3 class="product-title popup_cart_title">
                <a href="{{ route('product.details', $product->slug) }}">{{ $product->name }}</a>
            </h3>
            
            <div class="product-price">
                @if($isOnSale)
                    <del>${{ number_format($product->price, 2) }}</del>
                    <span>${{ number_format($product->sale_price, 2) }}</span>
                @else
                    <span>${{ number_format($product->price ?? $product->regular_price ?? 0, 2) }}</span>
                @endif
            </div>
            
            @if($showColorSwatches && count($colors) > 0)
                <div class="grid-color-swatch-area product-color">
                    <ul class="color-categories grid-color-swatch grid-image-swatch">
                        @foreach($colors->take(4) as $color)
                            <li class="{{ Str::slug($color->name) }} grid-swatch-selector">
                                <label data-toggle="tooltip" 
                                       data-placement="top"
                                       title="{{ $color->name }}"
                                       style="background: {{ $color->hex_code ?? $color->name }}; 
                                              @if($color->hex_code) background-color: {{ $color->hex_code }}; @endif
                                              @if(!$color->hex_code) background: {{ $color->name }}; @endif
                                              @if(strtolower($color->name) == 'white') border: 1px solid #ddd; @endif
                                              @if($color->image) background: url({{ $color->image }}) no-repeat scroll center center / cover; @endif">
                                </label>
                                <div class="product-url" style="display: none;">
                                    <a href="{{ route('product.details', $product->slug) }}?color={{ $color->id }}"></a>
                                </div>
                            </li>
                        @endforeach
                        @if(count($colors) > 4)
                            <li class="variant-plus-one">
                                <a href="{{ route('product.details', $product->slug) }}">
                                    <label>+{{ count($colors) - 4 }}</label>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>