@extends('client_layout.main')

@section('style')
<link href="{{ asset('client/shop/t/88/assets/jquery.fancybox-3.css') }}" rel="stylesheet" type="text/css" media="all" />
<style>
    /* Breadcrumb Banner */
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
    .breadcrumb-list > li > a {
        color: #071c1f;
    }
    .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:after,
    .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:nth-child(2) a:after {
        background-color: #071c1f;
    }
    .breadcrumb-list > li,
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

    /* Product Details Styles */
    .product-zoom {
        position: relative;
        overflow: hidden;
        cursor: crosshair;
     
    }
    .product-zoom img {
        width: 100%;
        height: auto;
    }
    .product-badge ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .product-badge ul li {
        display: inline-block;
        padding: 5px 15px;
        background: #f8796c;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        border-radius: 3px;
        margin-right: 5px;
    }
    .product-badge ul li.sale-badge {
        background: #f8796c;
    }
    /* .thumbnail-image.active {
        border: 2px solid #f8796c;
    } */
    .ltn__shop-details-small-img .single-small-img {
        cursor: pointer;
    }
    .ltn__shop-details-small-img .single-small-img img {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .ltn__shop-details-small-img .single-small-img img:hover {
        border-color: #f8796c;
    }
    
    /* Quantity Selector */
    .cart-plus-minus-box-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        background: #fff;
    }
    .cart-plus-minus-box-wrapper .qtybutton {
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
    .cart-plus-minus-box-wrapper .qtybutton:hover {
        background: #e9ecef;
    }
    .cart-plus-minus-box-wrapper .cart-plus-minus-box {
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
    .cart-plus-minus-box-wrapper .cart-plus-minus-box:focus {
        outline: none;
    }
    .cart-plus-minus-box-wrapper .cart-plus-minus-box::-webkit-outer-spin-button,
    .cart-plus-minus-box-wrapper .cart-plus-minus-box::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .cart-plus-minus-box-wrapper .cart-plus-minus-box[type=number] {
        -moz-appearance: textfield;
    }

    /* Color Swatches */
    .swatch {
        margin: 10px 0;
    }
    .swatch-header-area {
        margin-bottom: 10px;
    }
    .swatch-header-area .header {
        font-weight: 600;
        font-size: 14px;
    }
    .swatch-content-area {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .swatch-element {
        position: relative;
    }
    .swatch-element input {
        display: none;
    }
    .swatch-element label {
        display: block;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #ddd;
        transition: all 0.3s ease;
        background-size: cover;
        background-position: center;
    }
    .swatch-element input:checked + label {
        border-color: #f8796c;
        box-shadow: 0 0 0 2px #f8796c;
    }
    .swatch-element label:hover {
        border-color: #f8796c;
    }

    /* Product Tabs */
    .ltn__shop-details-tab-menu .nav {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }
    .ltn__shop-details-tab-menu .nav a {
        padding: 10px 25px;
        color: #071c1f;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 5px 5px 0 0;
        text-decoration: none;
    }
    .ltn__shop-details-tab-menu .nav a.active {
        background: #f8796c;
        color: #fff;
    }
    .ltn__shop-details-tab-menu .nav a:hover:not(.active) {
        background: #f5f5f5;
    }
    .tab-pane {
        display: none;
    }
    .tab-pane.active {
        display: block;
    }

    /* Payment Icons */
    .methods-of-payment img {
        padding: 0.2em;
        height: 35px;
        width: auto;
    }
    /* Zoom Overlay */
        .product-zoom {
            position: relative;
            overflow: hidden;
            cursor: crosshair;
        }

        .product-zoom .zoom-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: 200% auto;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 10;
            pointer-events: none;
            border-radius: 5px;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
        }

        .product-zoom:hover .zoom-overlay {
            opacity: 1;
        }

        .product-zoom img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        .product-zoom:hover img {
            transform: scale(1.02);
        }

        .product-zoom {
            cursor: crosshair;
        }
</style>
@endsection

@section('content')
<!-- BREADCRUMB AREA START -->
<div id="shopify-section-breadcrumb" class="shopify-section">
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white plr--9 breadcrumbs overlay-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2">
                        <div class="section-title-area ltn__section-title-2">
                            <h2 class="breadcrumbs-title section-title white-color">{{ $product->name }}</h2>
                            <div class="ltn__breadcrumb-list">
                                <ul class="breadcrumb-list">
                                    <li>
                                        <a href="/" title="Back to the home page">Home</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('shop.products') }}" title="">Shop</a>
                                        <span>{{ $product->name }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- BREADCRUMB AREA END -->

<main>
    <!-- PRODUCT DETAILS SECTION START -->
    <div id="shopify-section-template--14758549553270__main" class="shopify-section">

        <style data-shopify>#section-template--14758549553270__main{padding-top: 0px;
            padding-bottom: 80px;}
          @media (min-width: 768px) and (max-width: 991px) {
            #section-template--14758549553270__main{padding-top: 0px;
              padding-bottom: 80px;}
          }
          @media (max-width: 767px) {
            #section-template--14758549553270__main{padding-top: 0px;
              padding-bottom: 60px;}
          }</style><div class="ltn__shop-details-area pb-80" data-section="productTemplate" id="section-template--14758549553270__main">
                <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__shop-details-inner">
                            <div class="row">
                                <!-- Product Images Column -->
                                <div class="col-lg-6">
                                    <div class="ltn__shop-details-img-gallery">
                                        <!-- Large Images Slider -->
                                        <div class="ltn__shop-details-large-img" data-slick='{"initialSlide": 0}'>
                                            <!-- Featured Image -->
                                            <div class="single-large-img">
                                                <div class="product-zoom">
                                                    <a data-fancybox="gallery" class="fancybox-gallery" 
                                                       href="{{ $product->getFirstMediaUrl('featured_image') }}">
                                                       @if($product->hasMedia('featured_image'))

                                                            {{ $product->getFirstMedia('featured_image')->img('uniform')->attributes([
                                                                'class' => 'product_variant_image 22427808039030',
                                                                'alt' => $product->name,
                                                                'loading' => 'lazy',
                                                                'data-zoom-image' =>  $product->getFirstMediaUrl('featured_image'),
                                                                'data-image' => $product->getFirstMediaUrl('featured_image'),

                                                            ]) }}
                                                        @else
                                                            <img class="product_variant_image" width="600" height="600" alt="{{$product->name}}" loading="lazy"sizes="18vw"
                                                            src="{{asset('client/shop/files/default_product_image.png')}}">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="product-badge">
                                                    <ul>
                                                        @if($product->is_new)
                                                            <li class="sale-badge">New</li>
                                                        @endif
                                                        @if($product->is_featured)
                                                            <li class="sale-badge" style="background: #ff8c00;">Featured</li>
                                                        @endif
                                                        @if($product->isOutOfStock())
                                                            <li class="sale-badge" style="background: #dc3545;">Out of Stock</li>
                                                        @elseif($product->isLowStock())
                                                            <li class="sale-badge" style="background: #ffc107; color: #000;">Low Stock</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <!-- Gallery Images -->
                                            @if($product->hasMedia('gallery_images'))

                                                @foreach($product->getMedia('gallery_images') as $image)
                                                    <div class="single-large-img">
                                                        <div class="product-zoom">
                                                            <a data-fancybox="gallery" class="fancybox-gallery"
                                                            href="{{ $image->getUrl() }}">
                                                            {!! $image->img('uniform')->attributes([
                                                                 'class' => 'product_variant_image 22427808039030',
                                                                 'alt' => $product->name,
                                                                'loading' => 'lazy',
                                                                'data-zoom-image' =>  $image->getUrl(),
                                                                'data-image' => $image->getUrl(),

                                                            ]) !!}
                                                            </a>
                                                        </div>
                                                    </div>
                                                    
                                                @endforeach

                                            @endif
                                        </div>

                                        <!-- Thumbnail Images -->
                                        <div class="ltn__shop-details-small-img slick-arrow-2" id="ProductThumbs"
                                             data-slick="{'initialSlide': 0}">
                                            <!-- Featured Image Thumbnail -->
                                            <div class="single-small-img">
                                                <a href="javascript: void(0)"
                                                   data-image="{{ $product->getFirstMediaUrl('featured_image') }}"
                                                   data-zoom-image="{{ $product->getFirstMediaUrl('featured_image') }}"
                                                   class="thumbnail-image">

                                                        @if($product->hasMedia('featured_image'))

                                                            {{ $product->getFirstMedia('featured_image')->img('thumb')->attributes([
                                                                'alt' => $product->name,
                                                                'loading' => 'lazy',
                                                            ]) }}
                                                        @else
                                                            <img class="thumbnail-image " alt="{{$product->name}}" loading="lazy"sizes="18vw"
                                                            src="{{asset('client/shop/files/default_product_image.png')}}">
                                                        @endif
                                                    
                                                </a>
                                            </div>
                                            
                                            <!-- Gallery Thumbnails -->
                                            @if($product->hasMedia('gallery_images'))
                                                @foreach($galleryImages as $image)
                                                    <div class="single-small-img">
                                                        <a href="javascript: void(0)"
                                                        data-image="{{ $image->getUrl() }}"
                                                        data-zoom-image="{{ $image->getUrl() }}"
                                                        class="thumbnail-image">
                                                            {!! $image->img('thumb')->attributes([
                                                                'alt' => $product->name,
                                                            'loading' => 'lazy',
                                                        ]) !!}
                                                        </a>
                                                    </div>

                                                 
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <!-- Product Info Column -->
                                <div class="col-lg-6">
                                    <form method="post" action="{{ route('shop.cart.add') }}" id="AddToCartForm"
                                          class="product-content-inner" enctype="multipart/form-data">
                                        @csrf
                                        <div class="shop-details-info modal-product-info">
                                            <!-- Product Rating -->
                                            <div class="product-ratting">
                                                <span class="shopify-product-reviews-badge" data-id="{{ $product->id }}"></span>
                                            </div>
                                            
                                            <!-- Product Title -->
                                            <h1 id="popup_cart_title">{{ $product->name }}</h1>
                                            
                                            <!-- Product Price -->
                                            <div class="product-price">
                                                @if($product->compare_at_price)
                                                    <del>
                                                        <span id="ComparePrice" class="price-old">
                                                            ${{ number_format($product->compare_at_price, 2) }}
                                                        </span>
                                                    </del>
                                                @endif
                                                <span id="ProductPrice">${{ number_format($product->price ?? 0, 2) }}</span>
                                                
                                                @if($product->compare_at_price && $product->price < $product->compare_at_price)
                                                    @php
                                                        $discountPercent = round((($product->compare_at_price - $product->price) / $product->compare_at_price) * 100);
                                                    @endphp
                                                    <span class="single-product-label discount">
                                                        <span class="sale-title">Save </span>
                                                        <span class="percent-count save__price_box">
                                                            -<span class="sale__save--percent">{{ $discountPercent }}</span>%
                                                        </span>
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- Short Description -->
                                            <div class="modal-product-brief">
                                                <p>{{ Str::limit($product->description ?? 'No description available.', 200) }}</p>
                                            </div>
                                            
                                            <hr>
                                            
                                            <!-- Visitor Counter -->
                                            <div class="visitor-counter-area">
                                                <div>
                                                    <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/eye-animation.gif?v=106251934295271429031701360378"
                                                         width="auto" height="25" alt="live beacon"
                                                         style="height:20px; margin-right:10px; padding-bottom: 0px;">
                                                    <span id="txt">
                                                        <strong>{{ $visitorCount }}</strong> people are viewing this right now.
                                                    </span>
                                                </div>
                                            </div>
                                            <hr>
                                            
                                            <!-- Product Meta -->
                                            <div class="modal-product-meta ltn__product-details-menu-1">
                                                <ul>
                                                    <!-- Inventory/Availability -->
                                                    <li class="product-variant-inventory">
                                                        <strong class="inventory-title">Availability:</strong>
                                                        <span class="variant-inventory">
                                                            @if($product->isOutOfStock())
                                                                <span class="text-danger">Out of stock</span>
                                                            @elseif($product->isLowStock())
                                                                <span class="text-warning">{{ $product->stock_quantity }} left in stock</span>
                                                            @else
                                                                <span class="text-success">In stock</span>
                                                            @endif
                                                        </span>
                                                    </li>
                                                    
                                                    <!-- SKU -->
                                                    <li>
                                                        <strong>SKU:</strong>
                                                        <span class="variant-sku">{{ $product->sku }}</span>
                                                    </li>
                                                    
                                                    <!-- Brand/Vendor -->
                                                    @if($product->brand)
                                                        <li>
                                                            <strong>Vendor:</strong> 
                                                            <span>{{ $product->brand->name }}</span>
                                                        </li>
                                                    @endif
                                                    
                                                    <!-- Type/Category -->
                                                    @if($product->category)
                                                        <li>
                                                            <strong>Category:</strong> 
                                                            <span>{{ $product->category->name }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <hr>
                                            
                                            <!-- Variants / Color Swatches -->
                                            @if($product->color)
                                                <div class="modal-product-meta ltn__product-details-menu-1">
                                                    <ul>
                                                        <li>
                                                            <div class="ltn__tagcloud-widget ltn__size-widget clearfix">
                                                                <div class="product-variant-option pro-size color-option">
                                                                    <div class="swatch clearfix Color" data-option-index="0">
                                                                        <div class="swatch-header-area">
                                                                            <div class="header">Color : </div>
                                                                        </div>
                                                                        <div class="swatch-content-area">
                                                                            <div data-value="{{ $product->color->name }}" 
                                                                                 class="swatch-element color {{ $product->color->name }} available">
                                                                                <input id="swatch-0-{{ $product->color->name }}" 
                                                                                       type="radio" 
                                                                                       name="option-0" 
                                                                                       value="{{ $product->color->name }}" 
                                                                                       checked>
                                                                                <label data-toggle="tooltip" 
                                                                                       data-placement="top" 
                                                                                       title="{{ ucfirst($product->color->name) }}" 
                                                                                       for="swatch-0-{{ $product->color->name }}" 
                                                                                       style="background-color: {{ $product->color->hex_code ?? '#ccc' }}; 
                                                                                              background-image: url({{ $product->featured_image_url }}); 
                                                                                              background-size: cover;
                                                                                              background-position: center;">
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <hr>
                                            @endif
                                            
                                            <!-- Quantity and Add to Cart -->
                                            <div>
                                                <div class="ltn__product-details-menu-2">
                                                    <ul>
                                                        <!-- Quantity Selector -->
                                                        <li class="quantity-selector">
                                                            <div class="cart-plus-minus quantity">
                                                                <div class="cart-plus-minus-box-wrapper">
                                                                    <button type="button" class="qtybutton dec" onclick="decrementQuantity()">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <input type="text" 
                                                                           min="1" 
                                                                           step="1" 
                                                                           value="1" 
                                                                           name="quantity" 
                                                                           class="cart-plus-minus-box"
                                                                           id="quantityInput"
                                                                           max="{{ $product->stock_quantity }}">
                                                                    <button type="button" class="qtybutton inc" onclick="incrementQuantity()">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        
                                                        <!-- Add to Cart Button -->
                                                        <li>
                                                            <div class="product-cart-action">
                                                                <button type="submit" class="theme-btn-1 btn btn-effect-1" id="AddToCart"
                                                                        @if($product->isOutOfStock()) disabled @endif>
                                                                    <i class="fas fa-shopping-cart"></i>
                                                                    <span class="cart-title" id="AddToCartText">
                                                                        @if($product->isOutOfStock())
                                                                            Out of Stock
                                                                        @else
                                                                            Add to Cart
                                                                        @endif
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                
                                                <!-- Wishlist, Compare, Ask -->
                                                <div class="ltn__product-details-menu-3">
                                                    <ul>
                                                        <li>
                                                            <a class="action-wishlist tile-actions--btn flex wishlist-btn wishlist"
                                                               href="javascript: void(0)" 
                                                               onclick="addToWishlist('{{ $product->id }}')"
                                                               title="Add to wishlist">
                                                                <span class="add-wishlist"><i class="far fa-heart"></i> Add to wishlist</span>
                                                                <span class="loading-wishlist"><i class="fas fa-spinner fa-pulse"></i> Loading..</span>
                                                                <span class="remove-wishlist"><i class="fas fa-heart"></i> Remove From wishlist</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a title="" data-toggle="modal" href="javascript:void(0);"
                                                               class="compare" onclick="addToCompare('{{ $product->id }}')">
                                                                <i class="fas fa-exchange-alt"></i> Compare
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#ask-question" title="Ask about this product" data-toggle="modal">
                                                                <i class="far fa-envelope"></i> Ask about this product
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                            
                                            <!-- Estimated Delivery -->
                                            <div class="estimated-delivery-area product-text">
                                                <p>
                                                    <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/fast-delivery.png?v=51082720674517669131701360378"
                                                         width="auto" height="25" alt="Delivery Date"
                                                         style="height:25px; margin-right:10px; padding-bottom: 0px;">
                                                    Estimated Delivery Date :
                                                    <strong>
                                                        {{ $deliveryStart->format('d') }} - {{ $deliveryEnd->format('d F, Y') }}
                                                    </strong>
                                                </p>
                                                <h6>Return rules summary</h6>
                                                <ul>
                                                    <li>Returns accepted for 30 days</li>
                                                    <li>Free return shipping</li>
                                                    <li>No restocking fee</li>
                                                    <li>No final sale items</li>
                                                </ul>
                                            </div>
                                            <hr>
                                            
                                            <!-- Social Share -->
                                            <div class="ltn__social-media">
                                                <ul>
                                                    <li>Share:</li>
                                                    <li>
                                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                                           title="Share on Facebook" target="_blank">
                                                            <i class="fab fa-facebook-f"></i><span>Facebook</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://twitter.com/intent/tweet?text={{ $product->name }}&url={{ url()->current() }}"
                                                           title="Share on Twitter" target="_blank">
                                                            <i class="fab fa-twitter"></i><span>Twitter</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="https://pinterest.com/pin/create/button/?url={{ url()->current() }}&media={{ $product->featured_image_url }}&description={{ $product->name }}"
                                                           title="Share on Pinterest" target="_blank">
                                                            <i class="fab fa-pinterest"></i><span>Pinterest</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <hr>
                                            
                                            <!-- Secure Payment -->
                                            <div class="ltn__safe-checkout">
                                                <h5>
                                                    <p>Secure Payment Gateway</p>
                                                </h5>
                                                <div class="methods-of-payment">
                                                    <img loading="lazy" src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/visa-65d650f7.svg" height="35" alt="visa" />
                                                    <img loading="lazy" src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/mastercard-71a46b20.svg" height="35" alt="mastercard" />
                                                    <img loading="lazy" src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/paypal-a7c68b85.svg" height="35" alt="paypal" />
                                                    <img loading="lazy" src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/apple_pay-1721ebad.svg" height="35" alt="apple pay" />
                                                    <img loading="lazy" src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/google_pay-34c30515.svg" height="35" alt="google pay" />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="product_id" value="{{ Crypt::encrypt($product->id) }}" />
                                        <input type="hidden" name="product-id" value="{{ $product->id }}" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sticky Add to Cart (Optional) -->
            <form class="product-addtocart-sticky" action="{{ route('shop.cart.add') }}" method="post" style="display: none;">
                @csrf
                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}">
                <h2>{{ $product->name }}</h2>
                <select name="product_id">
                    <option value="{{ Crypt::encrypt($product->id) }}">{{ $product->name }} - ${{ number_format($product->price ?? 0, 2) }}</option>
                </select>
                <div class="quantity-selector">
                    <div class="cart-plus-minus quantity">
                        <input type="text" min="1" step="1" value="1" name="quantity" class="cart-plus-minus-box">
                    </div>
                </div>
                <button type="submit" class="theme-btn-1 btn btn-effect-1">Add to cart</button>
            </form>
        </div>
    </div>

    <!-- PRODUCT FEATURES SECTION -->
    <div id="shopify-section-template--14758549553270__1642600004294514c0" class="shopify-section">
        <div class="ltn__feature-area" id="section-template--14758549553270__1642600004294514c0">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-6">
                            <div class="ltn__feature-item ltn__feature-item-8">
                                <div class="ltn__feature-icon">
                                    <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/4-track.svg?v=66972495813929308381701360378" loading="lazy" alt="truck Icon">
                                </div>
                                <div class="ltn__feature-info">
                                    <h4>Free Shipping</h4>
                                    <p>Free shipping on orders over <strong>$99.</strong></p>
                                </div>
                            </div>
                            <div class="ltn__feature-item ltn__feature-item-8">
                                <div class="ltn__feature-icon">
                                    <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/9-money.svg?v=64672271261320180421701360378" loading="lazy" alt="Money Icon">
                                </div>
                                <div class="ltn__feature-info">
                                    <h4>Money Back</h4>
                                    <p>15 days money back guarantee.</p>
                                </div>
                            </div>
                            <div class="ltn__feature-item ltn__feature-item-8">
                                <div class="ltn__feature-icon">
                                    <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/10-credit-card.svg?v=67535653142018273091701360378" loading="lazy" alt="Credit Card Icon">
                                </div>
                                <div class="ltn__feature-info">
                                    <h4>Secure Checkout</h4>
                                    <p>We ensure 100% Payment Secure.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCT TABS SECTION -->
    <div id="shopify-section-template--14758549553270__single-product-tab" class="shopify-section">
        <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2 pb-85">
            <div class="container">
                <div class="ltn__shop-details-tab-menu">
                    <div class="nav">
                        <a href="#liton_tab_details_1_1" data-toggle="tab" role="tab" class="active show" aria-selected="true">
                            Description
                        </a>
                        <a href="#liton_tab_details_1_2" data-toggle="tab" role="tab">
                            Reviews
                        </a>
                        <a href="#shipping-policy" data-toggle="tab" role="tab">
                            Shipping Policy
                        </a>
                        <a href="#size-chart" data-toggle="tab" role="tab">
                            Size Chart
                        </a>
                    </div>
                </div>
                <div class="tab-content">
                    <!-- Description Tab -->
                    <div class="tab-pane fade show active" id="liton_tab_details_1_1" role="tabpanel">
                        <div class="ltn__shop-details-tab-content-inner">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="liton_tab_details_1_2" role="tabpanel">
                        <div class="ltn__shop-details-tab-content-inner">
                            <p>Reviews will be displayed here.</p>
                        </div>
                    </div>

                    <!-- Shipping Policy Tab -->
                    <div class="tab-pane fade" id="shipping-policy" role="tabpanel">
                        <div class="ltn__shop-details-tab-content-inner">
                            <h3>Shipping Policy</h3>
                            <p>All orders are processed within 1-2 business days (excluding weekends and holidays) after receiving your order confirmation email. You will receive another notification when your order has shipped.</p>
                            <h4>Domestic Shipping Rates</h4>
                            <p>Free shipping on orders over $99. For orders under $99, shipping is calculated at checkout.</p>
                            <h4>International Shipping</h4>
                            <p>We offer international shipping to most countries. Shipping charges will be calculated at checkout.</p>
                            <p>Your order may be subject to import duties and taxes (including VAT), which are incurred once a shipment reaches your destination country.</p>
                        </div>
                    </div>

                    <!-- Size Chart Tab -->
                    <div class="tab-pane fade" id="size-chart" role="tabpanel">
                        <div class="ltn__shop-details-tab-content-inner">
                            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f5f5f5;">
                                        <th style="padding: 10px; border: 1px solid #ddd;">Size</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Neck</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Chest</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Waist</th>
                                        <th style="padding: 10px; border: 1px solid #ddd;">Sleeve</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td style="padding: 10px; border: 1px solid #ddd;">S</td><td style="padding: 10px; border: 1px solid #ddd;">14-14½″</td><td style="padding: 10px; border: 1px solid #ddd;">34-36″</td><td style="padding: 10px; border: 1px solid #ddd;">28-30″</td><td style="padding: 10px; border: 1px solid #ddd;">32-33″</td></tr>
                                    <tr style="background: #f9f9f9;"><td style="padding: 10px; border: 1px solid #ddd;">M</td><td style="padding: 10px; border: 1px solid #ddd;">15-15½″</td><td style="padding: 10px; border: 1px solid #ddd;">38-40″</td><td style="padding: 10px; border: 1px solid #ddd;">32-34″</td><td style="padding: 10px; border: 1px solid #ddd;">33-34″</td></tr>
                                    <tr><td style="padding: 10px; border: 1px solid #ddd;">L</td><td style="padding: 10px; border: 1px solid #ddd;">16-16½″</td><td style="padding: 10px; border: 1px solid #ddd;">42-44″</td><td style="padding: 10px; border: 1px solid #ddd;">36-38″</td><td style="padding: 10px; border: 1px solid #ddd;">34-35″</td></tr>
                                    <tr style="background: #f9f9f9;"><td style="padding: 10px; border: 1px solid #ddd;">XL</td><td style="padding: 10px; border: 1px solid #ddd;">17-17½″</td><td style="padding: 10px; border: 1px solid #ddd;">46-48″</td><td style="padding: 10px; border: 1px solid #ddd;">40-42″</td><td style="padding: 10px; border: 1px solid #ddd;">35-36″</td></tr>
                                    <tr><td style="padding: 10px; border: 1px solid #ddd;">2XL</td><td style="padding: 10px; border: 1px solid #ddd;">18-18½″</td><td style="padding: 10px; border: 1px solid #ddd;">50-52″</td><td style="padding: 10px; border: 1px solid #ddd;">44-46″</td><td style="padding: 10px; border: 1px solid #ddd;">36-37″</td></tr>
                                    <tr style="background: #f9f9f9;"><td style="padding: 10px; border: 1px solid #ddd;">3XL</td><td style="padding: 10px; border: 1px solid #ddd;">19-19½″</td><td style="padding: 10px; border: 1px solid #ddd;">54-56″</td><td style="padding: 10px; border: 1px solid #ddd;">48-50″</td><td style="padding: 10px; border: 1px solid #ddd;">37-38″</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RELATED PRODUCTS SECTION -->
    @if($relatedProducts->count() > 0)
        <div id="shopify-section-template--14758549553270__related-product" class="shopify-section">
            <div class="ltn__product-slider-area ltn__product-gutter">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title-area text-center">
                                <h2 class="section-title">Related Products</h2>
                                <p>You might also like these products</p>
                            </div>
                        </div>
                    </div>
                    <div class="row ltn__product-slider-item-four-active slick-arrow-1"
                         data-slick='{"rows": 1, "slidesToShow": 4, "responsive":[
                             {"breakpoint":992, "settings": {"slidesToShow": 3, "arrows": false, "dots": true}},
                             {"breakpoint":768, "settings": {"slidesToShow": 2, "arrows": false, "dots": true}},
                             {"breakpoint":480, "settings": {"slidesToShow": 2, "slidesToScroll": 2, "arrows": false, "dots": true}}
                         ]}'>
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="col-lg-12">
                                <div class="ltn__product-item ltn__product-item-3 text-center product-wrapper">
                                    <div class="product-img">
                                        <a href="{{ route('shop.product.details', $relatedProduct->slug) }}" 
                                           class="image-position-wrapper primary_img" 
                                           style="padding-bottom: 100.0%;">
                                            <img src="{{ $relatedProduct->featured_image_url }}" 
                                                 alt="{{ $relatedProduct->name }}" 
                                                 loading="lazy" 
                                                 class="image-position-size">
                                        </a>
                                        <div class="product-badge">
                                            <ul>
                                                @if($relatedProduct->is_new)
                                                    <li class="sale-badge">New</li>
                                                @endif
                                                @if($relatedProduct->is_featured)
                                                    <li class="sale-badge" style="background: #ff8c00;">Featured</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-title">
                                            <a href="{{ route('shop.product.details', $relatedProduct->slug) }}">
                                                {{ $relatedProduct->name }}
                                            </a>
                                        </h3>
                                        <div class="product-price">
                                            <span>${{ number_format($relatedProduct->price ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ASK QUESTION MODAL -->
    <div class="ltn__modal-area modal fade" id="ask-question">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="contact-message">
                        <h4 class="contact-title">Have a question?</h4>
                        {{-- {{ route('contact.send') }} --}}
                        <form method="post" action="#">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="text" placeholder="Name" class="" name="name" id="ContactFormName" value="">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input type="email" placeholder="Email" class="" name="email" id="ContactFormEmail" value="">
                                </div>
                                <div class="col-12">
                                    <input type="text" id="ContactFormURL" name="url" placeholder="Product URL" value="{{ url()->current() }}">
                                </div>
                                <div class="col-12">
                                    <div class="contact2-textarea text-center">
                                        <textarea placeholder="Message" class="custom-textarea" name="message" id="ContactFormMessage"></textarea>
                                    </div>
                                    <div class="contact-btn">
                                        <button class="theme-btn-1 btn btn-effect-1" type="submit">Send</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script src="{{ asset('client/shop/t/88/assets/jquery.fancybox-3.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize Fancybox for gallery
        $('[data-fancybox="gallery"]').fancybox({
            loop: true,
            buttons: [
                'zoom',
                'slideShow',
                'fullScreen',
                'thumbs',
                'close'
            ],
        });
        
        // Thumbnail click to change main image
        $('.thumbnail-image').on('click', function(e) {
            e.preventDefault();
            var imageUrl = $(this).data('image');
            var zoomUrl = $(this).data('zoom-image');
            var parentGallery = $(this).closest('.ltn__shop-details-img-gallery');
            var mainImage = parentGallery.find('.single-large-img:first .product-zoom img');
            var zoomContainer = parentGallery.find('.single-large-img:first .product-zoom');
            
            // Update main image
            mainImage.attr('src', imageUrl);
            
            // Update fancybox link
            var fancyboxLink = parentGallery.find('.single-large-img:first .product-zoom a');
            fancyboxLink.attr('href', zoomUrl);
            
            // Remove active class from all thumbnails
            parentGallery.find('.thumbnail-image').removeClass('active');
            $(this).addClass('active');
        });
        
        // Custom zoom on hover (CSS-based)
        $('.product-zoom').each(function() {
            var container = $(this);
            var img = container.find('img');
            var originalSrc = img.attr('src');
            
            // Create zoom overlay
            var overlay = $('<div class="zoom-overlay"></div>');
            overlay.css({
                'position': 'absolute',
                'top': '0',
                'left': '0',
                'width': '100%',
                'height': '100%',
                'background-image': 'url(' + originalSrc + ')',
                'background-size': '110% auto',
                'background-repeat': 'no-repeat',
                'opacity': '0',
                'transition': 'opacity 0.3s ease',
                'z-index': '10',
                'pointer-events': 'none',
                'border-radius': '5px',
                'box-shadow': 'inset 0 0 20px rgba(0,0,0,0.1)'
            });
            container.append(overlay);
            
            // Show zoom on hover
            container.on('mouseenter', function() {
                overlay.css('opacity', '1');
                container.css('cursor', 'crosshair');
            });
            
            container.on('mouseleave', function() {
                overlay.css('opacity', '0');
                container.css('cursor', 'default');
            });
            
            // Track mouse movement for zoom effect
            container.on('mousemove', function(e) {
                var rect = this.getBoundingClientRect();
                var x = ((e.clientX - rect.left) / rect.width) * 100;
                var y = ((e.clientY - rect.top) / rect.height) * 100;
                overlay.css('background-position', x + '% ' + y + '%');
            });
        });
        
        // Update zoom overlay when thumbnail changes
        $(document).on('click', '.thumbnail-image', function() {
            var parentGallery = $(this).closest('.ltn__shop-details-img-gallery');
            var mainImage = parentGallery.find('.single-large-img:first .product-zoom img');
            var zoomContainer = parentGallery.find('.single-large-img:first .product-zoom');
            var overlay = zoomContainer.find('.zoom-overlay');
            
            if (overlay.length) {
                overlay.css('background-image', 'url(' + mainImage.attr('src') + ')');
            }
        });
    });

    // Quantity functions
    function incrementQuantity() {
        var input = $('#quantityInput');
        var currentVal = parseInt(input.val()) || 1;
        var maxVal = parseInt(input.attr('max')) || 999;
        
        if (currentVal < maxVal) {
            input.val(currentVal + 1);
        } else {
            Notiflix.Notify.warning('Maximum quantity is ' + maxVal);
        }
    }

    function decrementQuantity() {
        var input = $('#quantityInput');
        var currentVal = parseInt(input.val()) || 1;
        var minVal = parseInt(input.attr('min')) || 1;
        
        if (currentVal > minVal) {
            input.val(currentVal - 1);
        }
    }

    // Add to cart from product page
    $('#AddToCartForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        
        Notiflix.Loading.pulse('Adding to cart...');
        
        $.ajax({
            url: '{{ route("shop.cart.add") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Notiflix.Loading.remove();
                if (response.status === 'success') {
                    Notiflix.Notify.success(response.message || 'Product added to cart!');
                    updateCartCount();
                } else {
                    Notiflix.Notify.warning(response.message || 'Failed to add to cart');
                }
            },
            error: function(xhr) {
                Notiflix.Loading.remove();
                if (xhr.status === 400) {
                    Notiflix.Notify.warning(xhr.responseJSON.message || 'Product is not available');
                } else {
                    Notiflix.Notify.failure('An error occurred while adding to cart');
                }
            }
        });
    });
</script>
@endsection