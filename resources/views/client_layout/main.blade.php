<!doctype html>

<head>
    <!-- Basic and Helper page needs -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#071c1f">

    <link rel="shortcut icon" href="{{ asset('client/shop/files/favicon-4_32x32.png?v=1666681500') }}" type="image/png" />
    <!-- Title and description -->
    <title> New99 - Handmade Shop Shopify Theme</title>


    <meta name="description"
        content="New99 Handmade Shop Shopify Theme is a clean, modern, and minimal design for handmade store or boutique shop owners who want fast-loading &amp; top-quality online presence. Premium Shopify theme New99 has developed for beautiful &amp; unique, simple &amp; elegant eCommerce website." />


    <!-- Helpers --><!-- /snippets/social-meta-tags.liquid -->


    <meta property="og:type" content="website">
    <meta property="og:title" content="New99 - Handmade Shop Shopify Theme">
    <meta property="og:description"
        content="New99 Handmade Shop Shopify Theme is a clean, modern, and minimal design for handmade store or boutique shop owners who want fast-loading &amp; top-quality online presence. Premium Shopify theme New99 has developed for beautiful &amp; unique, simple &amp; elegant eCommerce website.">



    @include('client_layout.includes.header')
    @yield('style')




    <!-- CSS -->





    <!-- Make all your dynamic CSS and Color calls here -->
    <link href="{{ asset('client/shop/t/89/assets/skin-and-color.css?v=7481754498599465681701441143') }}"
        rel="stylesheet" type="text/css" media="all" /><!-- JS --><!-- Your update file include here -->






    <link rel="dns-prefetch" href="https://cdn.judge.me">
    <link rel="dns-prefetch" href="https://cdn1.judge.me">
    <link rel="dns-prefetch" href="https://api.judge.me">












    <noscript>
        <link rel="stylesheet" type="text/css" media="all"
            href="https://cdn.shopify.com/extensions/019e7346-c189-7f7a-92b8-27c96423875f/judgeme-543/assets/shopify_v2.css">
    </noscript>


    <!-- End of Judge.me Core -->




</head>

<body id="new99-handmade-shop-shopify-theme" class="template-index"><!-- preloader area start -->
    <div class="preloader d-none---" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->


    <div class="wrapper">

        <!-- BEGIN sections: header-group -->
        <div id="shopify-section-sections--14758559875190__header"
            class="shopify-section shopify-section-group-header-group">
            <style>
                /* Top Bar Noticfication */

                .announcement-bar {
                    background: #f5f4eb;
                    background: -moz-linear-gradient(left, #f5f4eb 0%, #f5f4eb 100%);
                    background: -webkit-gradient(left top, right top, color-stop(0%, #f5f4eb), color-stop(100%, #f5f4eb));
                    background: -webkit-linear-gradient(left, #f5f4eb 0%, #f5f4eb 100%);
                    background: -o-linear-gradient(left, #f5f4eb 0%, #f5f4eb 100%);
                    background: -ms-linear-gradient(left, #f5f4eb 0%, #f5f4eb 100%);
                    background: linear-gradient(to right, #f5f4eb 0%, #f5f4eb 100%);
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f5f4eb', endColorstr='#f5f4eb', GradientType=1);
                }

                .notification-entry p a.notification-button {
                    background-color: #f8796c;
                    border: 1px solid #f8796c;
                    color: #ffffff;
                }

                .notification-entry p a.notification-button:hover {
                    background-color: #071c1f;
                    border: 1px solid #071c1f;
                    color: #ffffff;
                }

                .notification-close-btn {
                    background-color: ;
                    border: 1px solid rgba(0, 0, 0, 0);
                    color: #e53e29;
                }

                .notification-close-btn:hover {
                    background-color: rgba(0, 0, 0, 0);
                    border: 1px solid rgba(0, 0, 0, 0);
                    color: #e53e29;
                }

                .notification-entry p {
                    color: #071c1f;
                }

                .announcement-bar::before {
                    background: rgba(0, 0, 0, 0);
                }
            </style>
            <div class="announcement-bar" data-section="announcementBar">
                <div class="container">
                    <div class="row">
                        <div class="notification-entry text-center col-12">
                            <div class="notification-entry-inner">
                                <p>Free shipping on orders over&nbsp;<strong>$99</strong>!</p>

                                <p><a class="notification-button" href="/collections/sale">Shop Now</a></p>

                            </div>
                            <button class="notification-close-btn"><i class="icon-cancel for-search-close"></i></button>
                        </div>
                    </div>
                </div>
            </div>










        </div>

        <div id="shopify-section-sections--14758545031286__header"
            class="shopify-section shopify-section-group-header-group">
            <style>
                .theme-logo img {
                    max-width: 150px;
                }
            </style>


            <!-- HEADER AREA START (header-4) -->
            <header id="section-sections--14758545031286__header" data-section="headerSection"
                class="ltn__header-area ltn__header-4 ltn__header-7  d-none d-sm-none d-md-none d-lg-none d-xl-block">
                <!-- ltn__header-middle-area start -->
                <div class="ltn__header-middle-area ltn__header-sticky ltn__sticky-bg-white">
                    <div class="container" "="">
              <div class=" row">
                    <div class="col">
                        <div class="site-logo"><a href="/" class="theme-logo">
                                <img srcset="{{ asset('client/shop/files/logo_150x.png') }}"
                                    src="{{ asset('client/shop/files/logo_150x.png') }}" loading="lazy" alt="New99">
                            </a></div>
                    </div>
                    <div class="col header-menu-column">
                        <div class="header-menu d-none d-xl-block">
                            <nav>
                                <div class="ltn__main-menu">
                                    <ul>
                                      
                                        @php
                                            $collections = App\Models\Category::active()
                                                            ->homepage()
                                                            ->get();
                                        @endphp
                                        <li class="menu-icon"><a href="/collections">Collections <span
                                                    class="menu-item-badge">Hot</span></a>
                                            <ul>

                                                @foreach ($collections as $collection)
                                                    <li><a href="{{route('shop.collections.show', $collection->slug)}}">{{$collection->name}}</a></li>

                                                @endforeach
                                             
                                                {{-- <li><a href="/collections/handcraft">Handcraft <span
                                                            class="menu-item-badge">Hot</span></a></li> --}}
                                               
                                            </ul>
                                        </li>
                                        <li><a href="#">About</a></li>

                                        <li><a href="#">Contact</a></li>
                                        <li><a href="/pages/faq">Faq</a></li>
                                       
                                        <li class="menu-icon"><a href="#">Policies</a>
                                            <ul>
                                                <li><a href="/policies/privacy-policy">Privacy Policy</a></li>
                                                <li><a href="/policies/refund-policy">Refund Policy</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <div class="col">
                        <div class="ltn__header-options">
                            <div class="ltn__drop-menu ltn__currency-menu default-currency-menu">
                                <ul>
                                    <li>

                                    </li>
                                    <li>
                                    </li>
                                </ul>
                            </div>

                            <!-- header-search-1 -->
                            <div class="header-search-wrap">
                                <div class="header-search-1">
                                    <div class="search-icon">
                                        <i class="icon-search for-search-show"></i>
                                        <i class="icon-cancel  for-search-close"></i>
                                    </div>
                                </div>
                                <div class="header-search-1-form"><predictive-search search-open="false">
                                        <form action="/search" method="get" role="search" class="">
                                            <input id="Search" type="search" name="q" value="" role="combobox"
                                                aria-expanded="false" aria-owns="predictive-search-results-list"
                                                aria-controls="predictive-search-results-list" aria-haspopup="listbox"
                                                aria-autocomplete="list" autocorrect="off" autocomplete="off"
                                                autocapitalize="off" spellcheck="false" class="predictive-search-input"
                                                placeholder="Search our store">
                                            <button type="submit" class="btn icon-fallback-text">
                                                <span><i class="icon-search"></i></span>
                                            </button>
                                            <input name="options[prefix]" type="hidden" value="last">
                                            <div id="predictive-search" class="predictive-search-item" tabindex="-1"
                                                style="display: none;"></div>
                                        </form>
                                    </predictive-search>


                                </div>
                            </div><!-- user-menu -->
                            <div class="ltn__drop-menu user-menu">
                                <ul>
                                    <li>
                                        @if(Auth::guard('customer')->check())

                                            <a href="#">
                                                <i class="icon-user"></i> {{ Auth::guard('customer')->user()->first_name }}
                                                <span class="caret"></span>
                                            </a>

                                        @else
                                            <a href="#"><i class="icon-user"></i></a>

                                        @endif
                                        <ul>
                                            <li><a href="/account">My Account</a></li>
                                            <li><a href="/pages/wishlist">Wishlist</a></li>
                                            <li><a href="/pages/compare">Compare</a></li>
                                            <li><a href="/search">Search</a></li>
                                            <li>
                                                @if(Auth::guard('customer')->check())
                                                <a href="#" id="logout-link" class="logout-link">Logout</a>
                                                <form id="logout-form" action="{{route('shop.logout')}}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                @else
                                                <a href="{{route('shop.login')}}">Login</a>
                                                @endif
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div><!-- mini-cart -->
                            <div class="mini-cart-icon">
                                <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                                    <i class="icon-shopping-cart"></i>
                                    <sup><span class="bigcounter">5</span></sup>
                                </a>
                            </div>
                            <!-- Mobile Menu Button -->
                            <div class="mobile-menu-toggle d-xl-none">
                                <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                    <svg viewBox="0 0 800 600">
                                        <path
                                            d=" M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340
                        300,200 300,200" id="top">
                        </path>
                        <path d="M300,320 L540,320" id="middle"></path>
                        <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190"
                            id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) ">
                        </path>
                        </svg>
                        </a>
                    </div>
                </div>
        </div>
    </div>
    </div>
    </div>
    <!-- ltn__header-middle-area end -->
    </header>
    <!-- HEADER AREA END -->






    <!-- Utilize Cart Menu Start -->
    <div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <span class="ltn__utilize-menu-title">Cart</span>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="mini-cart-product-area ltn__scrollbar cart-empty-title" style="display: none;">
                <h5>Your cart is currently empty.</h5>
            </div>

            <div class="mini-cart-product-area ltn__scrollbar cart-item-loop cart-item-display"
                style="display: block;">
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img"><a href="/products/q-s-product?variant=39654414188662"><img
                                src="https://cdn.shopify.com/s/files/1/0027/0952/0502/products/handmade-product_9.png?v=1669045254"
                                alt=""></a><span onclick="Shopify.removeItem(39654414188662)"
                            class="mini-cart-item-delete"><i class="icon-cancel"></i></span></div>
                    <div class="mini-cart-info">
                        <h6><a href="/products/q-s-product?variant=39654414188662">Q. s product</a></h6><span
                            class="mini-cart-quantity">2 x WS$ 108.00 WST</span>
                    </div>
                </div>
                <div class="mini-cart-item clearfix">
                    <div class="mini-cart-img"><a href="/products/w-unit-price-product?variant=39890775277686"><img
                                src="https://cdn.shopify.com/s/files/1/0027/0952/0502/products/28.png?v=1667235503"
                                alt=""></a><span onclick="Shopify.removeItem(39890775277686)"
                            class="mini-cart-item-delete"><i class="icon-cancel"></i></span></div>
                    <div class="mini-cart-info">
                        <h6><a href="/products/w-unit-price-product?variant=39890775277686">W. Unit Price Product -
                                white</a></h6><span class="mini-cart-quantity">3 x WS$ 53.00 WST</span>
                    </div>
                </div>
            </div>

            <div class="mini-cart-footer cart-item-display" style="display: block;">
                <div class="mini-cart-sub-total">
                    <h5>Subtotal: <span class="shopping-cart__total">WS$ 375.00 WST</span></h5>
                </div>
                <div class="btn-wrapper">
                    <a href="/cart" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                    <a href="/checkout" class="theme-btn-1 btn btn-effect-1">Checkout</a>
                </div>
                <p>Free shipping on orders over&nbsp;<strong>$99</strong></p>
            </div>
        </div>
    </div>
    <!-- Utilize Cart Menu End -->


    <!-- Utilize Mobile Menu Start -->
    <div class="mobile-header-area d-xl-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <!-- logo -->
                    <div class="logo"><a href="/" class="theme-logo">
                            <img srcset="{{ asset('client/shop/files/logo_150x.png') }} 2x"
                                src="{{ asset('client/shop/files/logo_150x.png') }}" loading="lazy" alt="New99">
                        </a></div>
                </div>
                <div class="col-6">
                    <div class="ltn__header-options">
                        <div class="ltn__drop-menu user-menu">
                            <ul>
                                <li>
                                        @if(Auth::guard('customer')->check())

                                            <a href="#">
                                                <i class="icon-user"></i> {{ Auth::guard('customer')->user()->first_name }}
                                                <span class="caret"></span>
                                            </a>

                                        @else
                                            <a href="#"><i class="icon-user"></i></a>

                                        @endif
                                        <ul>
                                            <li><a href="/account">My Account</a></li>
                                            <li><a href="/pages/wishlist">Wishlist</a></li>
                                            <li><a href="/pages/compare">Compare</a></li>
                                            <li><a href="/search">Search</a></li>
                                            <li>
                                                @if(Auth::guard('customer')->check())
                                                <a href="#" id="logout-link" class="logout-link">Logout</a>
                                                <form id="logout-form" action="{{route('shop.logout')}}" method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                @else
                                                <a href="{{route('shop.login')}}">Login</a>
                                                @endif
                                            </li>
                                        </ul>
                                </li>
                            </ul>
                        </div><!-- mini-cart -->
                        <div class="mini-cart-icon">
                            <a href="#ltn__utilize-cart-menu" class="ltn__utilize-toggle">
                                <i class="icon-shopping-cart"></i>
                                <sup><span class="bigcounter">5</span></sup>
                            </a>
                        </div>
                        <!-- Mobile Menu Button -->
                        <div class="mobile-menu-toggle d-xl-none">
                            <a href="#ltn__utilize-mobile-menu" class="ltn__utilize-toggle">
                                <svg viewBox="0 0 800 600">
                                    <path
                                        d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200"
                                        id="top"></path>
                                    <path d="M300,320 L540,320" id="middle"></path>
                                    <path
                                        d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190"
                                        id="bottom"
                                        transform="translate(480, 320) scale(1, -1) translate(-480, -318) ">
                                    </path>
                                </svg>
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ltn__utilize-mobile-menu" class="ltn__utilize ltn__utilize-mobile-menu">
        <div class="ltn__utilize-menu-inner ltn__scrollbar">
            <div class="ltn__utilize-menu-head">
                <div class="site-logo">

                    <a href="/" class="theme-logo">
                        <img loading="lazy" src="shop/files/logo_150x.png" alt="New99">
                    </a>
                </div>
                <button class="ltn__utilize-close">×</button>
            </div>
            <div class="ltn__utilize-menu-search-form"><predictive-search search-open="false">
                    <form action="/search" method="get" role="search" class="">
                        <input id="Search" type="search" name="q" value="" role="combobox"
                            aria-expanded="false" aria-owns="predictive-search-results-list"
                            aria-controls="predictive-search-results-list" aria-haspopup="listbox"
                            aria-autocomplete="list" autocorrect="off" autocomplete="off" autocapitalize="off"
                            spellcheck="false" class="predictive-search-input" placeholder="Search our store">
                        <button type="submit" class="btn icon-fallback-text">
                            <span><i class="icon-search"></i></span>
                        </button>
                        <input name="options[prefix]" type="hidden" value="last">
                        <div id="predictive-search" class="predictive-search-item" tabindex="-1"
                            style="display: none;">
                        </div>
                    </form>
                </predictive-search>


            </div>
            <div class="ltn__utilize-menu">
                <ul>
                    
                
                  
                    <li><span class="menu-expand"></span><a href="/collections">Collections <span
                                class="menu-item-badge">Hot</span></a>
                        <ul class="sub-menu">
                            @foreach ($collections as $collection)
                                <li><a href="{{route('shop.collections.show', $collection->slug)}}">{{$collection->name}}</a></li>

                            @endforeach
                        </ul>
                    </li>
                    <li><a href="#">About</a></li>

                    <li><a href="#">Contact</a></li>
                    <li><a href="/pages/faq">Faq</a></li>
                   
                    <li class="menu-icon"><a href="#">Policies</a>
                        <ul>
                            <li><a href="/policies/privacy-policy">Privacy Policy</a></li>
                            <li><a href="/policies/refund-policy">Refund Policy</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="ltn__utilize-buttons ltn__utilize-buttons-2">
                <ul>
                    <li>
                        <a href="/account" title="My Account">
                            <span class="utilize-btn-icon">
                                <i class="far fa-user"></i>
                            </span>
                            My Account
                        </a>
                    </li>
                    <li>
                        <a href="/pages/wishlist" title="Wishlist">
                            <span class="utilize-btn-icon">
                                <i class="far fa-heart"></i>
                            </span>
                            Wishlist
                        </a>
                    </li>
                    <li>
                        <a href="/cart" title="Shoping Cart">
                            <span class="utilize-btn-icon">
                                <i class="fas fa-shopping-cart"></i>
                                <sup><span class="bigcounter">5</span></sup>
                            </span>
                            Shopping Cart
                        </a>
                    </li>
                </ul>
            </div>
            <div class="ltn__social-media-2">
                <ul>
                    <li><a target="_blank" title="Facebook-f" href="#"><i class="fab fa-facebook-f"></i></a>
                    </li>


                    <li><a target="_blank" title="Twitter" href="#"><i class="fab fa-twitter"></i></a></li>



                    <li><a target="_blank" title="Youtube" href="#"><i class="fab fa-youtube"></i></a></li>


                    <li><a target="_blank" title="Vimeo" href="#"><i class="fab fa-vimeo"></i></a></li>


                    <li><a target="_blank" title="Tiktok" href="#"><i class="fab fa-tiktok"></i></a></li>


                </ul>
            </div>
        </div>
    </div>
    <!-- Utilize Mobile Menu End -->
    <div class="ltn__utilize-overlay" style="display: none;"></div>

    </div>

    @yield('breadcrumb')
    <!-- END sections: header-group -->
    <main>

        @yield('content')
       
       
    </main>

    <!-- BEGIN sections: footer-group -->
    <div id="shopify-section-sections--14650595573878__footer" class="shopify-section shopify-section-group-footer-group">
        <!-- FOOTER AREA START (ltn__footer-2 ltn__footer-color-2) -->
        <footer class="ltn__footer-area" data-section="footerActive" id="section-sections--14650595573878__footer">
            <div class="footer-top-area section-bg-1  ">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="footer-widget footer-about-widget text-center">
                                <div class="footer-logo">
                                    <div class="site-logo"><a href="/">
                                            <img srcset="//new-99.myshopify.com/cdn/shop/files/logo_150x.png?v=1666114323 1x, //new-99.myshopify.com/cdn/shop/files/logo_150x@2x.png?v=1666114323 2x"
                                                src="//new-99.myshopify.com/cdn/shop/files/logo_150x.png?v=1666114323"
                                                loading="lazy" alt="New99">
                                        </a>
                                    </div>
    
                                </div>
                                <div class="footer-address">
                                    <ul>
                                        <li>
                                            <div class="footer-address-info">
                                                <p><strong>Address</strong>: 3B, Sectior 1, Address, City, Country.</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="footer-address-info">
                                                <p><strong>Phone</strong>: +0123-456789</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="footer-address-info">
                                                <p><strong>Email</strong>: example@example.com</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="ltn__safe-checkout mt-20">
                                    <h5>
                                        <p>Guaranteed safe checkout</p>
                                    </h5>
                                    <div class="methods-of-payment"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/amazon-ec9fb491.svg"
                                            height="35" alt="amazon payments"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/apple_pay-1721ebad.svg"
                                            height="35" alt="apple pay"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/bitcoin-25bf0276.svg"
                                            height="35" alt="bitcoin"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/google_pay-34c30515.svg"
                                            height="35" alt="google pay"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/paypal-a7c68b85.svg"
                                            height="35" alt="paypal"><img loading="lazy"
                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/visa-65d650f7.svg"
                                            height="35" alt="visa"></div>
    
                                    <style>
                                        .methods-of-payment img {
                                            padding: 0.2em;
                                        }
    
                                        .lt-ie9 .methods-of-payment,
                                        .ie8 .methods-of-payment,
                                        .oldie .methods-of-payment {
                                            display: none;
                                        }
                                    </style>
                                </div>
                                <div class="ltn__social-media mt-20">
                                    <ul>
                                        <li>
                                            <a target="_blank" title="Facebook-f" href="https://www.facebook.com/shopify">
    
                                                <i class="fab fa-facebook-f"></i>
    
                                            </a>
                                        </li>
    
    
    
                                        <li>
                                            <a target="_blank" title="Twitter" href="https://twitter.com/shopify">
    
                                                <svg id="fi_5968958" enable-background="new 0 0 1226.37 1226.37"
                                                    viewBox="0 0 1226.37 1226.37" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="m727.348 519.284 446.727-519.284h-105.86l-387.893 450.887-309.809-450.887h-357.328l468.492 681.821-468.492 544.549h105.866l409.625-476.152 327.181 476.152h357.328l-485.863-707.086zm-144.998 168.544-47.468-67.894-377.686-540.24h162.604l304.797 435.991 47.468 67.894 396.2 566.721h-162.604l-323.311-462.446z">
                                                    </path>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                </svg>
    
                                            </a>
                                        </li>
    
    
    
    
    
                                        <li>
                                            <a target="_blank" title="Youtube" href="https://www.youtube.com/user/shopify">
    
                                                <i class="fab fa-youtube"></i>
    
                                            </a>
                                        </li>
    
    
    
                                        <li>
                                            <a target="_blank" title="Instagram" href="https://www.instagram.com/shopify/">
    
                                                <i class="fab fa-instagram"></i>
    
                                            </a>
                                        </li>
    
    
    
                                        <li>
                                            <a target="_blank" title="Tiktok" href="https://www.tiktok.com/@shopify">
    
                                                <i class="fab fa-tiktok"></i>
    
                                            </a>
                                        </li>
    
    
    
    
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <style>
                            .footer-logo img {
                                max-width: 150px;
                            }
                        </style>
                    </div>
                </div>
            </div>
            <style>
                #section-sections--14650595573878__footer .section-bg-1 {
                    background-color: #ece5dd;
                }
    
                .footer-widget .footer-title {
                    color: #ffffff;
                }
    
                .footer-widget,
                .footer-widget p,
                .ltn__footer-blog-item .ltn__blog-title,
                .footer-widget h5 {
                    color: #071c1f;
                }
    
                .ltn__footer-blog-item {
                    border-color: #103034;
                }
            </style>
            <div class="ltn__copyright-area ltn__copyright-2 ">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="site-logo-wrap">
                                <div class="ltn__copyright-design clearfix">
                                    <p>Copyright ©<strong>New99</strong> All Right Reserved.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 align-self-center">
                            <div class="ltn__copyright-menu text-right">
                                <ul>
                                    <li><a href="/policies/privacy-policy">Privacy Policy</a></li>
                                    <li><a href="/policies/refund-policy">Refund Policy</a></li>
                                    <li><a href="/policies/terms-of-service">Terms of Service</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                #section-sections--14650595573878__footer .ltn__copyright-area {
                    background: #ece5dd;
                    color: #071c1f;
    
                    border-top: 1px solid #f2f6f7;
    
                }
    
                #section-sections--14650595573878__footer .get-support::before {
                    background-color: rgba(0, 0, 0, 0);
                }
    
                #section-sections--14650595573878__footer .get-support-info h6,
                #section-sections--14650595573878__footer .get-support-info h4,
                #section-sections--14650595573878__footer .ltn__copyright-design p {
                    color: #071c1f
                }
            </style>
        </footer>
        <!-- FOOTER AREA END -->
    
    
    </div>
    <!-- END sections: footer-group -->
    </div>
    <div class="loading-modal compare_modal">Translation missing: en.general.search.loading</div>
    <div class="ajax-success-compare-modal compare_modal" id="moda-compare" tabindex="-1" role="dialog"
        style="display:none">
        <div class="overlay"> </div>
        <div class="modal-dialog modal-lg">
            <div class="modal-content content" id="compare-modal">
                <div class="modal-header">
                    <div class="modal-close">
                        <span class="compare-modal-close"><i class="icon-cancel for-search-close"></i></span>
                    </div>
                    <h4 class="modal-title">Compare Product</h4>
                </div>
                <div class="modal-body">
                    <div class="table-wrapper">
                        <table class="table table-hover table-responsive">
                            <thead>
                                <tr class="th-compare">
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="table-compare">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


  <!-- modalAddToCart -->
  <div class="modal fade ajax-popup ltn__add-to-cart-modal-area" id="modalAddToCart" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog white-modal modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="modal-content-text">
            <div class="popup-image">
              <img class="popupimage" src="">
            </div>
            <div class="popup-content">
              <h5 class="productmsg"></h5>
              <p class="added-cart"><i class="fa fa-check-circle"></i> Successfully added to your Cart</p> 
              <div class="btn-wrapper">
                <a href="/cart" class="theme-btn-1 btn btn-effect-1">View Cart</a>
                <a href="/checkout" class="theme-btn-2 btn btn-effect-2">Checkout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- modalAddToCart -->

<!-- modalAddToCart Error -->
  <div class="modal fade ajax-popup error-ajax-popup" id="modalAddToCartError" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog white-modal modal-md">
      <div class="modal-content ">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body">
          <div class="modal-content-text">
            <p class="error_message"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('client.partials.quick-view-modal')

  {{-- <div class="modal fade productModal ltn__sp-modal" id="quickViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
              <div class="qwick-view-left">
                <div class="quick-view-learg-img">
                  <div class="quick-view-tab-content tab-content">
                    <div class="product-main-image__item">
                      <div class="img_box_1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">
              <div class="qwick-view-right modal-product-info">
                <div class="qwick-view-content">
                  <div class="product-rating spr-badge-caption-none">
                    <div class="quick-view-rating rating">FROM_JS</div>
                  </div>
                  <h1 class="product_title">FROM_JS</h1>
                  <div class="product-price product-info__price price-part">
                    <span class="main">jsprice</span>
                    <span class="price-box__new">jsprice</span>
                  </div>
  
                  <div class="short-description product-des">FROM_JS</div>
  
                  <form id="add-item-qv" action="/cart/add" method="post">
                    <div class="quick-view-select variants select-option-part"></div>
                    <div class="quickview-plus-minus">
                      <div class="cart-plus-minus quantity">
                        <input type="text" min="1" step="1" value="1" name="quantity" class="cart-plus-minus-box">
                      </div>
                      <div class="quickview-btn-cart">
                        <button type="submit" class="addtocartqv theme-default-button----  theme-btn-1 btn btn-effect-1">Add to cart</button>
                      </div>
                    </div>
                   
                  </form>
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span class="close-icon" aria-hidden="true"><i class="icon-cancel  for-search-close"></i></span>
          </button>
        </div>
      </div>
    </div>
  </div> --}}

    {{-- <div class="quickViewModal_info" style="display: none;">
        <div class="button">Add to cart</div>
        <div class="button_added">Added</div>
        <div class="button_error">Limit Products</div>
        <div class="button_wait">Wait..</div>
    </div> --}}
    @include('client_layout.includes.scripts')

    {{-- <script async="async">  
        var mainImage = '';
        jQuery(function ($) {
      
          quiqview = function(product_handle) {
            Shopify.getProduct(product_handle);
          }
          Shopify.onProduct = function(product) {
            $('.viewfullinfo').attr('href', product.url);
      
            // product description without shortcode
            text_truncate = function(str, length, ending) {
              if (length == null) {
                length = 500;
              }
              if (ending == null) {
                ending = '...';
              }
              if (str.length > length) {
                return str.substring(0, length - ending.length) + ending;
              } else {
                return str;
              }
            };
      
            var _parent = '#quickViewModal';
            $(_parent+' .product_title').text(product.title);
           
      
      
            // product rating
            $(_parent+' .rating').empty();
            $(_parent+' .rating').append("<span class=\"shopify-product-reviews-badge\" data-id=\""+product.id+"\"></span>");
      
      
            //check variants
            var variant = '';
      
            for (i = 0; i < product.variants.length; i++) {
              if(product.variants[i].inventory_quantity > 0) {
                variant = product.variants[i];
                break;
              }
            }
      
            if(variant == '') {
              for (i = 0; i < product.variants.length; i++) {
                if(product.variants[i].inventory_policy == "continue") {
                  variant = product.variants[i];
                  break;
                }
              }
              if(variant == '') {
                variant = product.variants[0];
              }
            }
      
            mainImage = product.featured_image;
            var shopifyimgurl = variant.featured_image ? variant.featured_image.src : product.featured_image;
            var imgurl = "<img class=\"full-width\" alt=\"\" src = \""+shopifyimgurl+"\" >";
            jQuery(_parent+' .product-main-image__item .img_box_1').empty();
            jQuery(_parent+' .product-main-image__item .img_box_1').append(imgurl);
            jQuery(_parent+' .product-main-image__item .img_box_2').empty();
            jQuery(_parent+' .product-main-image__item .img_box_2').append(imgurl);
      
            // product description with shortcode
            var desc = product.description;
            if (desc.indexOf("[short_description]") >= 0) {
              desc = desc.split("[short_description]");
              desc = desc[1].split("[/short_description]");
              $(_parent+' .product-des').show();
              $(_parent+' .product-des').html(desc[0]);
            }
            else {
              $(_parent+' .product-des').html(text_truncate(product.description,250));
            }
      
            //set variants property
            var inv_qua = variant.inventory_quantity;
            //price
            if(variant.price < variant.compare_at_price) {
              $('.price-part .main').addClass('amount');
              $('.price-part .price-box__new').show();
              changePriceValue('.price-part .main', variant.compare_at_price);
              changePriceValue('.price-part .price-box__new', variant.price);
            }
            else {
              $('.price-part .price-box__new').hide();
              $('.price-part .main').removeClass('amount');
              changePriceValue('.price-part .main', variant.price);
            }
      
            // Variants select
            if(product.variants.length > 1) {
              var variants_margin = product.options.length == 2 ? 'variants_margin' : '';
      
              var select = '<select id="product-select-qv" name="id">';
              var selected = 'selected';
              for (i = 0; i < product.variants.length; i++) {
                var _var = product.variants[i];
                if(_var.available) {
                  select += '<option value="' + _var.id + '"' + selected +'>' + _var.title + ' - ' + Shopify.formatMoney(_var.price, "WS$ 1") + '</option>'
                                                                                                                         selected = '';
                                                                                                                         }
                                                                                                                         }
                                                                                                                         select += '</select>';
      
                                                                                                                         var variant_select = '<div class="variants_selects ' + variants_margin + '">';
                                                                                                                         variant_select += select;
                                                                                                                         variant_select += '</div><div class="divider divider--sm"></div>';
                                                                                                                         select = variant_select;
                                                                                                                         }
                                                                                                                         else {
                                                                                                                         var select = '<input type="hidden" name="id" value="' + product.variants[0].id + '" />';
                                                                                                                         }
                                                                                                                         $('.variants').empty();
                  $('.variants').html(select);
      
                  //parametres
                  setParametresText(_parent+' .product-sku', variant.sku);
                  if(jQuery(_parent + ' .product-sku').length) {
                    var $ava = jQuery(_parent + " .product-info__availabilitu");
                    if(variant.sku != "") {
                      if($ava.hasClass('pull-left')){ $ava.removeClass('pull-left') }
                    } else {
                      if(!$ava.hasClass('pull-left')){ $ava.addClass('pull-left') }
                    }
                  }
      
                  //quantity
                  var out_of_stock = false;
                  if(variant.inventory_management) {
                    if(inv_qua > 0) {
                      $(_parent+' .product-availability').text(inv_qua + " In Stock");
                                                               }
                    else {
                    out_of_stock = true;
                    $(_parent+' .product-availability').text("In Stock");
                                                            }
                                                                                                        }
                                                                                                        else {
                                                                                                        $(_parent+' .product-availability').text("Many in stock");
                                                                                                                                                 }
      
                                                                                                                                                 // button
                                                                                                                                                 if(!out_of_stock || variant.inventory_policy == "continue") {        
                        $('.product-available').show();
                        $('.product-disable').hide();
                        $('.addtocartqv').attr('id', product.id );
                      }
                      else {
                        $('.product-available').hide();
                        $('.product-disable').show();
                      }
      
                      if (product.available && product.variants.length > 1) {
                        new Shopify.OptionSelectors("product-select-qv", { product: product, onVariantSelected: selectCallbackQv, enableHistoryState: true });
      
                        if($('#quickViewModal .variants_selects .selector-wrapper').length > 0) {
                          $.each( jQuery('#quickViewModal .variants_selects .selector-wrapper'), function(index) {
                            $(this).find('label').text(product.options[index].name);
                          });
                        }
                      }
                      else {
                        jQuery('.currency .active').trigger('click');
                      }
                      selectGrid(_parent);
      
                      if($(".spr-badge").length > 0) {
                        $.getScript(window.location.protocol + "//productreviews.shopifycdn.com/assets/v4/spr.js");
                      }
      
                      if($(".selector-wrapper label").length) {
                        $(".selector-wrapper label").each(function( index ) {
                          $(this).text(jQuery(this).text() + ":");
                        });
                      }
      
                      $(_parent).modal('show');
      
                      if( !( 'ontouchstart' in window ) &&
                         !navigator.msMaxTouchPoints &&
                         !navigator.userAgent.toLowerCase().match( /windows phone os 7/i ) ) return false;
      
                      $j('body').css("top", -$j('body').scrollTop());
                      $j('body').addClass("no-scroll");
                      $j('.close').click(function(){
                        var top = parseInt($j('body').css("top").replace("px", ""))*-1;
                        $j('body').removeAttr("style");
                        $j('body').removeClass("no-scroll");
                        $j('body').scrollTop(top);
                      });
                    }
      
                    function setParametresText(obj, value) {
                      if(value != '') {
                        $(obj).parent().show();
                        $(obj).text(value);
                      }
                      else {
                        $(obj).parent().hide();
                      }
                    }
      
                    function changePriceValue (cell, value) {
                      $(cell).html(Shopify.formatMoney(value, "WS$ 1"));
                                                       };
      
                                                       });
      
      
      
                                   var selectCallbackQv = function(variant, selector) {
      
                        var _parent = '#quickViewModal';
                        var _parentprice = _parent + ' .price-part';
                        if (!variant) {
                          jQuery(_parent + " .price-box").hide();
                          jQuery(_parent + " .qwt").hide();
                          jQuery(_parent + " .control-console").hide();
                          jQuery(_parent + ' .addtocartqv').attr('disabled','disabled');
                          jQuery(_parent + ' .addtocartqv').text('Make a selection');
                                                                 return false;
                                                                 }
      
                                                                 jQuery(_parent + " .price-box").show();
                          jQuery(_parent + " .qwt").show();
                          jQuery(_parent + " .control-console").show();
      
                          if(variant.price < variant.compare_at_price){
                            jQuery(_parentprice + ' .main').addClass('price-box__old');
                            jQuery(_parentprice + ' .price-box__new').show();
                            changePriceValue(_parentprice + ' .main', variant.compare_at_price);
                            changePriceValue(_parentprice + ' .price-box__new', variant.price);
                          } else {
                            jQuery(_parentprice + ' .price-box__new').hide();
                            jQuery(_parentprice + ' .main').removeClass('price-box__old');
                            changePriceValue(_parentprice + ' .main', variant.price);
                          }
      
                          newVariantTextDataQv(_parent + ' .product-sku', variant.sku);
      
                          if(jQuery(_parent + ' .product-sku').length) {
                            var $ava = jQuery(_parent + " .product-info__availabilitu");
                            if(variant.sku != "") {
                              if($ava.hasClass('pull-left')){ $ava.removeClass('pull-left') }
                            } else {
                              if(!$ava.hasClass('pull-left')){ $ava.addClass('pull-left') }
                            }
                          }
      
                          if (variant.available) {
                            if (variant.inventory_management == null) {
                              jQuery(_parent + " .product-availability").text("Many in stock");
                                                                              } else {
                                                                              jQuery(_parent + " .product-availability").text(" Many in stock");
                                                                                                                              }
                                                                                                                              } else {
                                                                                                                              jQuery(_parent + " .product-availability").text("Sold Out");
            }
      
            var shopifyimgurl = variant.featured_image ? variant.featured_image.src : mainImage;
            var imgurl = "<img class=\"full-width\" alt=\"\" src = \""+shopifyimgurl+"\" >";
            if(jQuery(_parent+' .product-main-image__item .img_box_1').children().length > 0) {
                                var detach = jQuery(_parent+' .product-main-image__item .img_box_1 img').detach();
                                jQuery(_parent+' .product-main-image__item .img_box_2').empty();
                                jQuery(_parent+' .product-main-image__item .img_box_2').append(detach);
                              }
                              jQuery(_parent+' .product-main-image__item .img_box_1').empty();
                              jQuery(_parent+' .product-main-image__item .img_box_1').append(imgurl);
      
                              if (variant && variant.available) {
                                jQuery(_parent + ' .addtocartqv').removeAttr('disabled');
                                jQuery(_parent + ' .addtocartqv').html('Add to Cart');
                                                                       jQuery(_parent + " .control-console").show();
                              } else {
                                jQuery(_parent + ' .addtocartqv').attr('disabled','disabled');
                                jQuery(_parent + ' .addtocartqv').text('Make a selection');
                                                                       jQuery(_parent + " .control-console").hide();
                              }
      
                              jQuery('.currency .active').trigger('click');
                            };
      
                            function changePriceValue (cell, value) {
                              jQuery(cell).html(Shopify.formatMoney(value, "WS$ 1"));
                                                                    };
      
                                                                    function newVariantTextDataQv (obj, value) {
                                                if(value != '') {
                                jQuery(obj).parent().show();
                                jQuery(obj).text(value);
                              }
                              else {
                                jQuery(obj).parent().hide();
                              }
                            };
      
      
                            function selectGrid(_parent) {
                              setTimeout(timeout, 5);
                              function timeout() {
                                if(jQuery(_parent + " .selector-wrapper").length > 2){
                                  jQuery(_parent + " .single-option-selector").addClass("select--wd");
                                } else if(jQuery(_parent + " .selector-wrapper").length == 1){
                                  jQuery(_parent + " .single-option-selector").before("<label></label>");
                                                                                      jQuery(_parent + " .single-option-selector").addClass("select--wd");
                                }
                              }
                            };
      </script>
       --}}
      <!-- END QUICKVIEW PRODUCT -->
    <script>
        $(window).on('load', function() {
            /*-----------------
                    preloader
                ------------------*/
            if ($('#preloader').length) {
                var preLoder = $("#preloader");
                preLoder.fadeOut(1000);

            };
        });
    </script>

    <script defer="defer">
        var nToggle = $('.notification-close-btn');
        nToggle.on('click', function() {
            $('.announcement-bar').slideToggle();
        })
    </script>

    {{-- <script>
        jQuery('.addtocartqv').click(function(e) {
            e.preventDefault();
            Shopify.addItemFromFormStart('add-item-qv', jQuery(this).attr('id'));
        });
    </script> --}}

<script>
    $(".theme-default-pagination .disabled a").removeAttr("href");
    $(".theme-default-pagination li.active a").removeAttr("href");
</script>

<script>
    $(document).ready(function() {
    // Handle logout with AJAX
    $('#logout-link').on('click', function(e) {
        e.preventDefault();
        
        // Show confirmation dialog
        Notiflix.Confirm.show(
            'Logout Confirmation',
            'Are you sure you want to logout?',
            'Yes',
            'No',
            function() {
                // Submit logout via AJAX
                $.ajax({
                    url: $('#logout-form').attr('action'),
                    type: 'POST',
                    data: $('#logout-form').serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        Notiflix.Loading.pulse('Logging out...');
                    },
                    success: function(response) {
                        Notiflix.Loading.remove();
                        if (response.status === 'success') {
                            Notiflix.Notify.success('Logged out successfully');
                            setTimeout(function() {
                                window.location.href = response.redirect || "{{route('shop.home')}}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure('Logout failed. Please try again.');
                    }
                });
            }
        );
    });
});
</script>
    @yield('scripts')

    @stack('scripts')
</body>

</html>
