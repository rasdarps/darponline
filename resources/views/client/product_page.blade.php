@extends('client_layout.main')

@section('style')

<link href="{{asset('client/shop/t/88/assets/jquery.fancybox-3.css')}}"
    rel="stylesheet" type="text/css" media="all" />
    <style>
        /* Breadcurmb Banner */
        .breadcrumbs {
        background: #f2f4f5 ;
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
        .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:after, .ltn__breadcrumb-inner-2 .ltn__breadcrumb-list ul li:nth-child(2) a:after {
        background-color: #071c1f;
        }
        .breadcrumb-list > li, .ltn__breadcrumb-color-white .ltn__breadcrumb-list li:last-child {
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
    <style data-shopify>
        #section-template--14758549553270__main {
            padding-top: 0px;
            padding-bottom: 80px;
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #section-template--14758549553270__main {
                padding-top: 0px;
                padding-bottom: 80px;
            }
        }

        @media (max-width: 767px) {
            #section-template--14758549553270__main {
                padding-top: 0px;
                padding-bottom: 60px;
            }
        }
    </style>
@endsection
@section('content')

    <div id="shopify-section-breadcrumb" class="shopify-section"><!-- BREADCRUMB AREA START -->


        <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white plr--9 breadcrumbs overlay-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2">
                            <div class="section-title-area ltn__section-title-2">
                                <h2 class="breadcrumbs-title section-title white-color">Product</h2>
                                <div class="ltn__breadcrumb-list">
                                    <ul class="breadcrumb-list">
                                        <li>
                                            <a href="/" title="Back to the home page">Home</a>
                                        </li>
                                        <li>





                                            <a href="/collections/handcraft" title="">Handcraft</a>



                                            <span>W. Unit Price Product</span>


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



    <main>
        <div id="shopify-section-template--14758549553270__main" class="shopify-section">
            
            <div class="ltn__shop-details-area pb-80" data-section="productTemplate"
                id="section-template--14758549553270__main">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ltn__shop-details-inner">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="ltn__shop-details-img-gallery">
                                            <div class="ltn__shop-details-large-img" data-slick='{
                                                    "initialSlide": 0
                                                }'>
                                                <div class="single-large-img">
                                                    <div class="product-zoom"><a data-fancybox="gallery"
                                                            class="fancybox-gallery"
                                                            href="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503">
                                                            <img class="product_variant_image 22364573335670"
                                                                data-media-id="22364573335670"
                                                                data-zoom-image="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503"
                                                                data-image="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503"
                                                                src="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503"
                                                                loading="lazy">
                                                        </a>
                                                    </div>
                                                    <div class="product-badge">
                                                        <ul></ul>
                                                    </div>
                                                </div>

                                                <div class="single-large-img">
                                                    <div class="product-zoom"><a data-fancybox="gallery"
                                                            class="fancybox-gallery"
                                                            href="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503">
                                                            <img class="product_variant_image 22364573335670"
                                                                data-media-id="22364573368438"
                                                                data-zoom-image="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503"
                                                                data-image="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503"
                                                                src="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503"
                                                                loading="lazy">
                                                        </a>
                                                    </div>
                                                    <div class="product-badge">
                                                        <ul></ul>
                                                    </div>
                                                </div>

                                                <div class="single-large-img">
                                                    <div class="product-zoom"><a data-fancybox="gallery"
                                                            class="fancybox-gallery"
                                                            href="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503">
                                                            <img class="product_variant_image 22364573335670"
                                                                data-media-id="22364573401206"
                                                                data-zoom-image="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503"
                                                                data-image="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503"
                                                                src="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503"
                                                                loading="lazy">
                                                        </a>
                                                    </div>
                                                    <div class="product-badge">
                                                        <ul></ul>
                                                    </div>
                                                </div>

                                                <div class="single-large-img">
                                                    <div class="product-zoom"><a data-fancybox="gallery"
                                                            class="fancybox-gallery"
                                                            href="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503">
                                                            <img class="product_variant_image 22364573335670"
                                                                data-media-id="22364573433974"
                                                                data-zoom-image="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503"
                                                                data-image="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503"
                                                                src="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503"
                                                                loading="lazy">
                                                        </a>
                                                    </div>
                                                    <div class="product-badge">
                                                        <ul></ul>
                                                    </div>
                                                </div>

                                                <div class="single-large-img">
                                                    <div class="product-zoom"><a data-fancybox="gallery"
                                                            class="fancybox-gallery"
                                                            href="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504">
                                                            <img class="product_variant_image 22364573335670"
                                                                data-media-id="22364573466742"
                                                                data-zoom-image="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504"
                                                                data-image="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504"
                                                                src="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504"
                                                                loading="lazy">
                                                        </a>
                                                    </div>
                                                    <div class="product-badge">
                                                        <ul></ul>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="ltn__shop-details-small-img slick-arrow-2" id="ProductThumbs"
                                                data-slick='{
                                                                    "initialSlide": 0
                                                                }'>

                                                <div class="single-small-img"><a data-thumbnail-id="22364573335670"
                                                        data-media-id="22364573335670" href="javascript: void(0)"
                                                        data-image="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503"
                                                        data-zoom-image="//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503"
                                                        class="thumbnail-image ">
                                                        <img src="//new-99.myshopify.com/cdn/shop/products/28_x110@2x.png?v=1667235503"
                                                            alt="W. Unit Price Product" loading="lazy">
                                                    </a>
                                                </div>

                                                <div class="single-small-img"><a data-thumbnail-id="22364573368438"
                                                        data-media-id="22364573368438" href="javascript: void(0)"
                                                        data-image="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503"
                                                        data-zoom-image="//new-99.myshopify.com/cdn/shop/products/29_1024x1024.png?v=1667235503"
                                                        class="thumbnail-image ">
                                                        <img src="//new-99.myshopify.com/cdn/shop/products/29_x110@2x.png?v=1667235503"
                                                            alt="W. Unit Price Product" loading="lazy">
                                                    </a>
                                                </div>

                                                <div class="single-small-img"><a data-thumbnail-id="22364573401206"
                                                        data-media-id="22364573401206" href="javascript: void(0)"
                                                        data-image="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503"
                                                        data-zoom-image="//new-99.myshopify.com/cdn/shop/products/30_1024x1024.png?v=1667235503"
                                                        class="thumbnail-image ">
                                                        <img src="//new-99.myshopify.com/cdn/shop/products/30_x110@2x.png?v=1667235503"
                                                            alt="W. Unit Price Product" loading="lazy">
                                                    </a>
                                                </div>

                                                <div class="single-small-img"><a data-thumbnail-id="22364573433974"
                                                        data-media-id="22364573433974" href="javascript: void(0)"
                                                        data-image="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503"
                                                        data-zoom-image="//new-99.myshopify.com/cdn/shop/products/31_1024x1024.png?v=1667235503"
                                                        class="thumbnail-image ">
                                                        <img src="//new-99.myshopify.com/cdn/shop/products/31_x110@2x.png?v=1667235503"
                                                            alt="W. Unit Price Product" loading="lazy">
                                                    </a>
                                                </div>

                                                <div class="single-small-img"><a data-thumbnail-id="22364573466742"
                                                        data-media-id="22364573466742" href="javascript: void(0)"
                                                        data-image="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504"
                                                        data-zoom-image="//new-99.myshopify.com/cdn/shop/products/32_1024x1024.png?v=1667235504"
                                                        class="thumbnail-image ">
                                                        <img src="//new-99.myshopify.com/cdn/shop/products/32_x110@2x.png?v=1667235504"
                                                            alt="W. Unit Price Product" loading="lazy">
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <form method="post" action="/contact#contact_form" id="contact_form"
                                            accept-charset="UTF-8" class="contact-form"><input type="hidden"
                                                name="form_type" value="contact" /><input type="hidden" name="utf8"
                                                value="✓" /></form>
                                        <form method="post" action="/cart/add" id="AddToCartForm" accept-charset="UTF-8"
                                            class="product-content-inner" enctype="multipart/form-data"><input type="hidden"
                                                name="form_type" value="product" /><input type="hidden" name="utf8"
                                                value="✓" />
                                            <div class="shop-details-info modal-product-info">
                                                <div class="product-ratting">
                                                    <span class="shopify-product-reviews-badge"
                                                        data-id="6803175768182"></span>
                                                </div>
                                                <h1 id="popup_cart_title">W. Unit Price Product</h1>
                                                <div class="product-price">

                                                    <del><span id="ComparePrice" class="price-old">WS$
                                                            58.00</span></del><span id="ProductPrice">WS$ 53.00</span><span
                                                        class="single-product-label discount"><span class="sale-title">Save
                                                        </span><span class="percent-count save__price_box">-<span
                                                                class="sale__save--percent">9</span>%</span></span>

                                                    <!-- For Unit Price  -->
                                                    <small class="unit_price_box caption ">
                                                        <dd>
                                                            <span id="product__unit_price">WS$ 53.00</span>
                                                            <span aria-hidden="true">/</span>
                                                            <span id="product__unit_price_value">100g
                                                            </span>
                                                        </dd>
                                                    </small>
                                                    <!-- For Unit Price  -->

                                                </div>
                                                <div class="modal-product-brief">
                                                    <p>Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris.
                                                        Praesent adipiscing. Phasellus ullamcorper ipsum rutrum nunc. Nunc
                                                        nonummy metus....</p>
                                                </div>
                                                <hr>
                                                <div class="visitor-counter-area">
                                                    <div onload="startTime()">
                                                        <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/eye-animation.gif?v=106251934295271429031701360378"
                                                            width="auto" height="25" alt="live beacon" alt="Visitor Counter"
                                                            style="height:20px; margin-right:10px; padding-bottom: 0px;">
                                                        <span id="txt"><strong>10</strong> people are viewing this right
                                                            now.</span>
                                                    </div>
                                                </div>
                                                <hr>
                                                <script>
                                                    function startTime() {
                                                        const today = new Date();
                                                        const h = today.getHours();
                                                        const m = today.getMinutes();
                                                        const s = today.getSeconds();
                                                        let count = 0;
                                                        const max = 20;
                                                        const min = 15;
                                                        const range = max - min;

                                                        if (s < 11) {
                                                            count = 0.73 * max + m / 10;
                                                        } else if (s > 12 && s < 30) {
                                                            count = 0.63 * max + m / 10;
                                                        } else if (s > 31 && s < 45) {
                                                            count = min + 0.55 * range + m / 10;
                                                        } else if (s > 46 && s < 51) {
                                                            count = 0.89 * range + min - s + 44 + m / 10;
                                                        } else if (s === 52 || s === 55 || s === 58 || s === 59) {
                                                            count = 0.92 * max - s + 54 + m / 10;
                                                        } else {
                                                            count = 0.87 * max + m / 10;
                                                        }

                                                        const formattedCount = Math.round(count);
                                                        const displayText = `${formattedCount}`;
                                                        document.querySelector('#txt strong').innerHTML = displayText;
                                                        const t = setTimeout(startTime, 5000);
                                                    }

                                                    function checkTime(i) {
                                                        return i < 10 ? `0${i}` : i.toString();
                                                    }
                                                    startTime();
                                                </script>
                                                <div class="modal-product-meta ltn__product-details-menu-1">
                                                    <ul>
                                                        <script>
                                                            var inv_qty = {};

                                                            inv_qty[39890775277686] = 0;

                                                            inv_qty[39890775310454] = 0;

                                                            inv_qty[39890775343222] = 0;

                                                            inv_qty[39890775375990] = 0;

                                                            inv_qty[39890775408758] = 0;

                                                        </script>

                                                        <li>
                                                            <strong>SKU:</strong>
                                                            <span class="variant-sku">1510</span>
                                                        </li>
                                                        <li>
                                                            <strong>Vendor:</strong>
                                                            <span>
                                                                <a href="">Vendor J</a>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <strong>Type:</strong>
                                                            <span>
                                                                <a href="">Type J</a>
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <hr>
                                                <div class="product-page-countdown-area">
                                                    <h5>Limited Time Offer! Ends in:</h5>
                                                    <div class="ltn__countdown ltn__countdown-2"
                                                        data-countdown="2025-10-31"></div>
                                                </div>
                                                <hr>
                                                <div class="modal-product-meta ltn__product-details-menu-1">
                                                    <ul>
                                                        <li>
                                                            <div class="ltn__tagcloud-widget ltn__size-widget clearfix">
                                                                <div class="product-variant-option pro-size color-option">
                                                                    <select name="id" id="productSelect"
                                                                        class="product-single__variants"
                                                                        style="display:none;">
                                                                        <option selected="selected" data-sku="1510"
                                                                            value="39890775277686">white - WS$ 53.00 WST
                                                                        </option>
                                                                        <option data-sku="1511" value="39890775310454">gold
                                                                            - WS$ 53.00 WST</option>
                                                                        <option data-sku="1512" value="39890775343222">gray
                                                                            - WS$ 53.00 WST</option>
                                                                        <option data-sku="1513" value="39890775375990">
                                                                            magenta - WS$ 53.00 WST</option>
                                                                        <option data-sku="1514" value="39890775408758">
                                                                            maroon - WS$ 53.00 WST</option>
                                                                    </select>














                                                                    <style>
                                                                        label[for="product-select-option-0"] {
                                                                            display: none;
                                                                        }

                                                                        #product-select-option-0 {
                                                                            display: none;
                                                                        }

                                                                        #product-select-option-0+.custom-style-select-box {
                                                                            display: none !important;
                                                                        }
                                                                    </style>
                                                                    <script>$(window).load(function () { $('.selector-wrapper:eq(0)').hide(); });</script>






                                                                    <div class="swatch clearfix Color"
                                                                        data-option-index="0">
                                                                        <div class="swatch-header-area">
                                                                            <div class="header">Color : </div>
                                                                        </div>

                                                                        <div class="swatch-content-area">







                                                                            <div data-value="white"
                                                                                class="swatch-element color white available swatch-bg-image">
                                                                                <input id="swatch-0-white" type="radio"
                                                                                    name="option-0" value="white" checked />

                                                                                <label data-toggle="tooltip"
                                                                                    data-placement="top" title="White"
                                                                                    for="swatch-0-white" style="background-color: white; 
            
                                                                                                background-image: url(//new-99.myshopify.com/cdn/shop/products/28_thumb.png?v=1667235503);">

                                                                                </label>

                                                                            </div>


                                                                            <script>
                                                                                jQuery('.swatch[data-option-index="0"] .white').removeClass('soldout').addClass('available').find(':radio').removeAttr('disabled');
                                                                            </script>







                                                                            <div data-value="gold"
                                                                                class="swatch-element color gold available swatch-bg-image">
                                                                                <input id="swatch-0-gold" type="radio"
                                                                                    name="option-0" value="gold" />

                                                                                <label data-toggle="tooltip"
                                                                                    data-placement="top" title="Gold"
                                                                                    for="swatch-0-gold" style="background-color: gold; 
            
                                                                                                background-image: url(//new-99.myshopify.com/cdn/shop/products/29_thumb.png?v=1667235503);">

                                                                                </label>

                                                                            </div>


                                                                            <script>
                                                                                jQuery('.swatch[data-option-index="0"] .gold').removeClass('soldout').addClass('available').find(':radio').removeAttr('disabled');
                                                                            </script>







                                                                            <div data-value="gray"
                                                                                class="swatch-element color gray available swatch-bg-image">
                                                                                <input id="swatch-0-gray" type="radio"
                                                                                    name="option-0" value="gray" />

                                                                                <label data-toggle="tooltip"
                                                                                    data-placement="top" title="Gray"
                                                                                    for="swatch-0-gray" style="background-color: gray; 
            
                                                                                                background-image: url(//new-99.myshopify.com/cdn/shop/products/30_thumb.png?v=1667235503);">

                                                                                </label>

                                                                            </div>


                                                                            <script>
                                                                                jQuery('.swatch[data-option-index="0"] .gray').removeClass('soldout').addClass('available').find(':radio').removeAttr('disabled');
                                                                            </script>







                                                                            <div data-value="magenta"
                                                                                class="swatch-element color magenta available swatch-bg-image">
                                                                                <input id="swatch-0-magenta" type="radio"
                                                                                    name="option-0" value="magenta" />

                                                                                <label data-toggle="tooltip"
                                                                                    data-placement="top" title="Magenta"
                                                                                    for="swatch-0-magenta" style="background-color: magenta; 
            
                                                                                            background-image: url(//new-99.myshopify.com/cdn/shop/products/31_thumb.png?v=1667235503);">

                                                                                </label>

                                                                            </div>


                                                                            <script>
                                                                                jQuery('.swatch[data-option-index="0"] .magenta').removeClass('soldout').addClass('available').find(':radio').removeAttr('disabled');
                                                                            </script>







                                                                            <div data-value="maroon"
                                                                                class="swatch-element color maroon available swatch-bg-image">
                                                                                <input id="swatch-0-maroon" type="radio"
                                                                                    name="option-0" value="maroon" />

                                                                                <label data-toggle="tooltip"
                                                                                    data-placement="top" title="Maroon"
                                                                                    for="swatch-0-maroon" style="background-color: maroon; 
            
                                                                                            background-image: url(//new-99.myshopify.com/cdn/shop/products/32_thumb.png?v=1667235504);">

                                                                                </label>

                                                                            </div>


                                                                            <script>
                                                                                jQuery('.swatch[data-option-index="0"] .maroon').removeClass('soldout').addClass('available').find(':radio').removeAttr('disabled');
                                                                            </script>


                                                                        </div>
                                                                    </div>




                                                                    <script>
                                                                        jQuery(function () {
                                                                            jQuery('.swatch :radio').change(function () {
                                                                                var optionIndex = jQuery(this).closest('.swatch').attr('data-option-index');
                                                                                var optionValue = jQuery(this).val();
                                                                                jQuery(this)
                                                                                    .closest('form')
                                                                                    .find('.single-option-selector')
                                                                                    .eq(optionIndex)
                                                                                    .val(optionValue)
                                                                                    .trigger('change');
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>
                                                                <style>
                                                                    .product-variant-option .selector-wrapper {
                                                                        display: none;
                                                                    }
                                                                </style>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <hr>

                                                <style>
                                                    .swatch-element>label,
                                                    .product-variant-option .selector-wrapper select {
                                                        text-transform: capitalize;
                                                    }
                                                </style>
                                                <div>
                                                    <div class="ltn__product-details-menu-2 ">
                                                        <ul>
                                                            <li class="quantity-selector">
                                                                <div class="cart-plus-minus quantity">
                                                                    <input type="text" min="1" step="1" value="1"
                                                                        name="quantity" class="cart-plus-minus-box">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div class="product-cart-action"><button type="submit"
                                                                        class="theme-btn-1 btn btn-effect-1" id="AddToCart">
                                                                        <i class="fas fa-shopping-cart"></i>
                                                                        <span class="cart-title" id="AddToCartText">Add to
                                                                            cart</span></button>
                                                                    <script>
                                                                        jQuery('#AddToCart').click(function (e) {
                                                                            e.preventDefault();
                                                                            Shopify.addItemFromFormStart('AddToCartForm', 6803175768182);
                                                                        }); 
                                                                    </script>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div data-shopify="payment-button"
                                                                    class="shopify-payment-button">
                                                                    <shopify-accelerated-checkout recommended="null"
                                                                        fallback="{&quot;supports_subs&quot;:true,&quot;supports_def_opts&quot;:true,&quot;name&quot;:&quot;buy_it_now&quot;,&quot;wallet_params&quot;:{}}"
                                                                        access-token="afc6e708737957516cf68eba2b07676b"
                                                                        buyer-country="WS" buyer-locale="en"
                                                                        buyer-currency="WST"
                                                                        variant-params="[{&quot;id&quot;:39890775277686,&quot;requiresShipping&quot;:true},{&quot;id&quot;:39890775310454,&quot;requiresShipping&quot;:true},{&quot;id&quot;:39890775343222,&quot;requiresShipping&quot;:true},{&quot;id&quot;:39890775375990,&quot;requiresShipping&quot;:true},{&quot;id&quot;:39890775408758,&quot;requiresShipping&quot;:true}]"
                                                                        shop-id="2709520502" disable-compat>
                                                                        <div class="shopify-payment-button__button"
                                                                            role="button" disabled aria-hidden="true"
                                                                            style="background-color: transparent; border: none">
                                                                            <div class="shopify-payment-button__skeleton">
                                                                                &nbsp;</div>
                                                                        </div>
                                                                    </shopify-accelerated-checkout> </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="ltn__product-details-menu-3">
                                                        <ul>
                                                            <li><a class="action-wishlist tile-actions--btn flex wishlist-btn wishlist"
                                                                    href="javascript: void(0)" button-wishlist
                                                                    data-product-handle="w-unit-price-product"
                                                                    title="Add to wishlist">
                                                                    <span class="add-wishlist"><i class="far fa-heart"></i>
                                                                        Add to wishlist</span>
                                                                    <span class="loading-wishlist"><i
                                                                            class="fas fa-spinner fa-pulse"></i>
                                                                        Loading..</span>
                                                                    <span class="remove-wishlist"><i
                                                                            class="fas fa-heart"></i> Remove From
                                                                        wishlist</span>
                                                                </a><a class="go-to-wishlist" href="/pages/wishlist"
                                                                    title=""><i class="far fa-heart"></i> Go to wishlist</a>
                                                            </li>
                                                            <li>
                                                                <a title="" data-toggle="modal" href="javascript:void(0);"
                                                                    class="compare" data-pid="w-unit-price-product">
                                                                    Compare
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#ask-question" title="Ask about this product"
                                                                    data-toggle="modal">
                                                                    <i class="far fa-envelope"></i> Ask about this product
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="estimated-delivery-area product-text">
                                                    <p>
                                                        <img src="//new-99.myshopify.com/cdn/shop/t/88/assets/fast-delivery.png?v=51082720674517669131701360378"
                                                            width="auto" height="25" alt="Delivery Date"
                                                            style="height:25px; margin-right:10px; padding-bottom: 0px;" />
                                                        Estimated Delivery Date :
                                                        <strong>







                                                            02
                                                            -

                                                            03 June, 2026.

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
                                                <div class="ltn__social-media">
                                                    <ul>
                                                        <li>Share:</li>
                                                        <li><a href="//www.facebook.com/sharer.php?u=https://new-99.myshopify.com/products/w-unit-price-product"
                                                                title="Share on Facebook" target="_blank"><i
                                                                    class="fab fa-facebook-f"></i><span>Facebook</span></a>
                                                        </li>
                                                        <li><a href="//twitter.com/share?text=W.%20Unit%20Price%20Product&amp;url=https://new-99.myshopify.com/products/w-unit-price-product"
                                                                title="Share on Twitter" target="_blank"><i
                                                                    class="fab fa-twitter"></i><span>Twitter</span></a></li>

                                                        <li><a href="//pinterest.com/pin/create/button/?url=https://new-99.myshopify.com/products/w-unit-price-product&amp;media=//new-99.myshopify.com/cdn/shop/products/28_1024x1024.png?v=1667235503&amp;description=W.%20Unit%20Price%20Product"
                                                                title="Share on Pinterest" target="_blank"><i
                                                                    class="fab fa-pinterest"></i><span>Pinterest</span></a>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <hr>
                                                <div class="ltn__safe-checkout">
                                                    <h5>
                                                        <p>Secure Payment Gateway</p>
                                                    </h5>
                                                    <div class="methods-of-payment"><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/amazon-ec9fb491.svg"
                                                            height="35" alt="amazon payments" /><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/apple_pay-1721ebad.svg"
                                                            height="35" alt="apple pay" /><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/bitcoin-25bf0276.svg"
                                                            height="35" alt="bitcoin" /><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/google_pay-34c30515.svg"
                                                            height="35" alt="google pay" /><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/paypal-a7c68b85.svg"
                                                            height="35" alt="paypal" /><img loading="lazy"
                                                            src="//new-99.myshopify.com/cdn/shopifycloud/storefront/assets/payment_icons/visa-65d650f7.svg"
                                                            height="35" alt="visa" /></div>

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

                                                <div class="product-complementary"
                                                    data-url="/recommendations/products?section_id=template--14758549553270__main&product_id=6803175768182&limit=4&intent=complementary">
                                                </div>




                                            </div><input type="hidden" name="product-id" value="6803175768182" /><input
                                                type="hidden" name="section-id" value="template--14758549553270__main" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="product-addtocart-sticky" action="/cart/add" method="post">
                    <img src="//new-99.myshopify.com/cdn/shop/products/28_thumb.png?v=1667235503">
                    <h2>W. Unit Price Product</h2>
                    <select name="id">


                        <option data-sku="1510" value="39890775277686">

                            white -

                            WS$ 53.00 WST
                        </option>



                        <option data-sku="1511" value="39890775310454">

                            gold -

                            WS$ 53.00 WST
                        </option>



                        <option data-sku="1512" value="39890775343222">

                            gray -

                            WS$ 53.00 WST
                        </option>



                        <option data-sku="1513" value="39890775375990">

                            magenta -

                            WS$ 53.00 WST
                        </option>



                        <option data-sku="1514" value="39890775408758">

                            maroon -

                            WS$ 53.00 WST
                        </option>


                    </select>
                    <div class="quantity-selector">
                        <div class="cart-plus-minus quantity">
                            <input type="text" min="1" step="1" value="1" name="quantity" class="cart-plus-minus-box">
                        </div>
                    </div>
                    <button type="submit" class="theme-btn-1 btn btn-effect-1">Add to cart</button>
                </form>
            </div>
































           
            <div class="ltn__modal-area modal fade" id="ask-question">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="contact-message">
                                <h4 class="contact-title">Have a question?</h4>
                                <form method="post" action="/contact#contact_form" id="contact_form" accept-charset="UTF-8"
                                    class="contact-form"><input type="hidden" name="form_type" value="contact" /><input
                                        type="hidden" name="utf8" value="✓" />
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <input type="text" placeholder="Name" class="" name="contact[name]"
                                                id="ContactFormName" value="">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <input type="email" placeholder="Email" class="" name="contact[email]"
                                                id="ContactFormEmail" value="">
                                        </div>
                                        <div class="col-12">
                                            <input type="text" id="ContactFormURL" name="contact[url]"
                                                placeholder="Product URL"
                                                value="https://new-99.myshopify.com/collections/handcraft/products/w-unit-price-product">
                                        </div>
                                        <div class="col-12">
                                            <div class="contact2-textarea text-center">
                                                <textarea placeholder="Message" class="custom-textarea" name="contact[body]"
                                                    id="ContactFormMessage"></textarea>
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
        </div>
        <div id="shopify-section-template--14758549553270__1642600004294514c0" class="shopify-section">
            <style>
                #section-template--14758549553270__1642600004294514c0 .ltn__feature-item-8 .ltn__feature-info h4 {
                    margin-bottom: 5px;
                    color: #071c1f;
                }

                #section-template--14758549553270__1642600004294514c0 .ltn__feature-item-8 .ltn__feature-info p {
                    color: #071c1f;
                }

                #section-template--14758549553270__1642600004294514c0.before-bg-bottom-2:before {
                    background-color: #f2f6f7;
                }
            </style>
            <style data-shopify>
                #section-template--14758549553270__1642600004294514c0 {
                    padding-top: 0px;
                    padding-bottom: 80px;
                }

                @media (min-width: 768px) and (max-width: 991px) {
                    #section-template--14758549553270__1642600004294514c0 {
                        padding-top: 0px;
                        padding-bottom: 50px;
                    }
                }

                @media (max-width: 767px) {
                    #section-template--14758549553270__1642600004294514c0 {
                        padding-top: 0px;
                        padding-bottom: 50px;
                    }
                }
            </style>
            <div class="ltn__feature-area  " id="section-template--14758549553270__1642600004294514c0"
                data-section="serviceSection">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ltn__feature-item-box-wrap ltn__feature-item-box-wrap-2 ltn__border section-bg-6">
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon"><img
                                            src="//new-99.myshopify.com/cdn/shop/t/88/assets/4-track.svg?v=66972495813929308381701360378"
                                            loading="lazy" alt="truck Icon"></div>
                                    <div class="ltn__feature-info">
                                        <h4>Free Shipping</h4>
                                        <p>Free shipping on orders over <strong>$99.</strong></p>
                                    </div>
                                </div>
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon"><img
                                            src="//new-99.myshopify.com/cdn/shop/t/88/assets/9-money.svg?v=64672271261320180421701360378"
                                            loading="lazy" alt="Money Icon"></div>
                                    <div class="ltn__feature-info">
                                        <h4>Money Back</h4>
                                        <p>15 days money back guarantee.</p>
                                    </div>
                                </div>
                                <div class="ltn__feature-item ltn__feature-item-8">
                                    <div class="ltn__feature-icon"><img
                                            src="//new-99.myshopify.com/cdn/shop/t/88/assets/10-credit-card.svg?v=67535653142018273091701360378"
                                            loading="lazy" alt="Credit Card Icon"></div>
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
        <div id="shopify-section-template--14758549553270__single-product-tab" class="shopify-section">
            <!-- Shop Tab Start -->
            <div class="ltn__shop-details-tab-inner ltn__shop-details-tab-inner-2 pb-85">
                <div class="container">
                    <div class="ltn__shop-details-tab-menu">
                        <div class="nav">

                            <a href="#liton_tab_details_1_1" data-toggle="tab" role="tab" class="active show"
                                aria-selected="true">
                                Description
                            </a>


                            <a href="#liton_tab_details_1_2" data-toggle="tab" role="tab">
                                Reviews
                            </a>



                            <a href="#shipping-policy-19c5d1b1-f6ec-4a12-8971-5c54c1b0ae02" data-toggle="tab" role="tab"
                                aria-selected="false">
                                Shipping Policy
                            </a><a href="#size-chart-63b7460d-6341-4fb3-980f-de9b010bf039" data-toggle="tab" role="tab"
                                aria-selected="false">
                                Size Chart
                            </a>
                        </div>
                    </div>
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="liton_tab_details_1_1" role="tabpanel">
                            <div class="ltn__shop-details-tab-content-inner">
                                <p>Integer ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent
                                    adipiscing. Phasellus ullamcorper ipsum rutrum nunc. Nunc nonummy metus. Vestibulum
                                    volutpat pretium libero. Cras id dui. Aenean ut eros et nisl sagittis vestibulum. Nullam
                                    nulla eros, ultricies sit amet, nonummy id, imperdiet feugiat, pede. Sed lectus. Donec
                                    mollis hendrerit risus. Phasellus nec sem in justo pellentesque facilisis. Etiam
                                    imperdiet imperdiet orci. Nunc nec neque. Phasellus leo dolor, tempus non, auctor et,
                                    hendrerit quis, nisi. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere
                                    imperdiet, leo. Maecenas malesuada.</p>
                                <p>Praesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate
                                    arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci
                                    luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit
                                    erat euismod orci, ac placerat dolor lectus quis orci. Phasellus consectetuer vestibulum
                                    elit. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Vestibulum
                                    fringilla pede sit amet augue. In turpis. Pellentesque posuere.</p>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="liton_tab_details_1_2" role="tabpanel">
                            <div class="ltn__shop-details-tab-content-inner">
                                <div>
                                    <div id="shopify-block-AbzE2MExOZ1I4Zk5zS__bb17de32-c436-4a1e-8efb-db143f5409ad"
                                        class="shopify-block shopify-app-block" data-block-handle="review_widget">


                                        <div style='clear:both'></div>
                                        <div id='judgeme_product_reviews' class='jdgm-widget jdgm-review-widget'
                                            data-product-title='W. Unit Price Product' data-id='6803175768182'
                                            data-product-id='6803175768182' data-widget="review" data-shop-reviews="false"
                                            data-shop-reviews-count="0" data-cart-eligible-template="false"
                                            data-shop-average-rating="5.00" data-shop-review-count="2"
                                            data-empty-state="empty_widget" data-entry-point="review_widget.js"
                                            data-entry-key="review-widget/main.js"
                                            data-block-id="AbzE2MExOZ1I4Zk5zS__bb17de32-c436-4a1e-8efb-db143f5409ad"
                                            data-customer-logged-in="false" style='max-width: 1200px; margin: 0 auto;'>






                                        </div>




                                    </div>
                                </div>
                                <div>
                                </div>
                                <div>
                                </div>

                            </div>
                        </div>



                        <div class="tab-pane fade" id="shipping-policy-19c5d1b1-f6ec-4a12-8971-5c54c1b0ae02"
                            role="tabpanel">
                            <div class="ltn__shop-details-tab-content-inner">
                                <h3>Shipping policy</h3>
                                <p><strong><em>It's important to start by clarifying to customers that your order processing
                                            times are separate from the shipping times they see at checkout. </em></strong>
                                </p>
                                <p>All orders are processed within X to X business days (excluding weekends and holidays)
                                    after receiving your order confirmation email. You will receive another notification
                                    when your order has shipped. </p>
                                <p><strong><em>Include any other pertinent information towards the beginning, such as
                                            potential delays due to a high volume of orders or postal service problems that
                                            are outside of your control.</em></strong></p>
                                <h3>Domestic Shipping Rates and Estimates</h3>
                                <p><strong><em>For calculated shipping rates: </em></strong>Shipping charges for your order
                                    will be calculated and displayed at checkout. </p>
                                <p><strong><em>For simple flat rate shipping:</em></strong><em> </em>We offer $X flat rate
                                    shipping to [list countries]. </p>
                                <p><strong><em>You can also emphasize any free shipping thresholds you offer (e.g. free
                                            shipping for orders over $75).</em></strong><strong><em> For multiple shipping
                                            options</em></strong><strong><em>, you can list carrier options, prices, and
                                            delivery times in a table.</em></strong></p>
                                <table border="solid black">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p><strong>Shipping option</strong></p>
                                            </td>
                                            <td>
                                                <p><strong>Estimated delivery time</strong></p>
                                            </td>
                                            <td>
                                                <p><strong>Price</strong></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 1</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 2</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 3</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h4></h4>
                                <h3>Local delivery</h3>
                                <p><strong><em>If you offer local delivery or in-store pickup to customers in your area, you
                                            can dedicate a section of your shipping policy page to explain the process or
                                            create a separate shipping page specifically for local customers.
                                        </em></strong><em><br></em></p>
                                <p>Free local delivery is available for orders over $X within [area of coverage]. For orders
                                    under $X, we charge $X for local delivery.</p>
                                <p>Deliveries are made from [delivery hours] on [available days]. We will contact you via
                                    text message with the phone number you provided at checkout to notify you on the day of
                                    our arrival. </p>
                                <p><strong><em>You can list out the ZIP/postal codes you service and/or consider embedding a
                                            map here so customers can easily see if they are within your local delivery
                                            range.</em></strong></p>
                                <h3>In-store pickup</h3>
                                <p>You can skip the shipping fees with free local pickup at [list the locations where
                                    in-store pickup is available]. After placing your order and selecting local pickup at
                                    checkout, your order will be prepared and ready for pick up within X to X business days.
                                    We will send you an email when your order is ready along with instructions. </p>
                                <p>Our in-store pickup hours are [store hours] on [available days of the week]. Please have
                                    your order confirmation email with you when you come.</p>
                                <h3>International Shipping</h3>
                                <p>We offer international shipping to the following countries: [list of countries]. </p>
                                <p><strong><em>If relevant you can also include countries you don’t ship to:</em></strong>
                                    At this time, we do not ship to [list of countries]. </p>
                                <p><strong><em>If you’re using calculated shipping rates: </em></strong>Shipping charges for
                                    your order will be calculated and displayed at checkout. </p>
                                <p><strong><em>If you offer multiple international shipping options, you can list them in a
                                            table as well. You can include broader delivery timelines (e.g. 8 to 20 days)
                                            for international shipping since expectations can vary greatly depending on the
                                            destination.</em></strong></p>
                                <table border="solid black">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p><strong>Shipping option</strong></p>
                                            </td>
                                            <td>
                                                <p><strong>Estimated delivery time</strong></p>
                                            </td>
                                            <td>
                                                <p><strong>Price</strong></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 1</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 2</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>Option 3</p>
                                            </td>
                                            <td>
                                                <p>X to X business days</p>
                                            </td>
                                            <td>
                                                <p>$X</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p> </p>
                                <p>Your order may be subject to import duties and taxes (including VAT), which are incurred
                                    once a shipment reaches your destination country. [Your Company] is not responsible for
                                    these charges if they are applied and are your responsibility as the customer.</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="size-chart-63b7460d-6341-4fb3-980f-de9b010bf039" role="tabpanel">
                            <div class="ltn__shop-details-tab-content-inner">
                                <table border="0" cellpadding="0" cellspacing="0">
                                    <colgroup>
                                        <col width="14%">
                                        <col width="14%">
                                        <col width="14%">
                                        <col width="14%">
                                        <col width="14%">
                                        <col width="15%">
                                        <col width="15%">
                                    </colgroup>
                                    <tbody>
                                        <tr class="headrow">
                                            <td><span>Size</span></td>
                                            <td><span>Neck</span></td>
                                            <td><span>Chest</span></td>
                                            <td><span>Waist</span></td>
                                            <td><span>Sleeve</span></td>
                                        </tr>
                                        <tr class="oddrow">
                                            <td><span>S</span></td>
                                            <td><span>14-14½″</span></td>
                                            <td><span>34-36″</span></td>
                                            <td><span>28-30″</span></td>
                                            <td><span>32-33″</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>M</span></td>
                                            <td><span>15-15½″</span></td>
                                            <td><span>38-40″</span></td>
                                            <td><span>32-34″</span></td>
                                            <td><span>33-34″</span></td>
                                        </tr>
                                        <tr class="oddrow">
                                            <td><span>L</span></td>
                                            <td><span>16-16½″</span></td>
                                            <td><span>42-44″</span></td>
                                            <td><span>36-38″</span></td>
                                            <td><span>34-35″</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>XL</span></td>
                                            <td><span>17-17½″</span></td>
                                            <td><span>46-48″</span></td>
                                            <td><span>40-42″</span></td>
                                            <td><span>35-36″</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>2XL</span></td>
                                            <td><span>18-18½″</span></td>
                                            <td><span>50-52″</span></td>
                                            <td><span>44-46″</span></td>
                                            <td><span>36-37″</span></td>
                                        </tr>
                                        <tr class="oddrow">
                                            <td><span>3XL</span></td>
                                            <td><span>19-19½″</span></td>
                                            <td><span>54-56″</span></td>
                                            <td><span>48-50″</span></td>
                                            <td><span>37-38″</span></td>
                                        </tr>
                                        <tr>
                                            <td><span>4XL</span></td>
                                            <td><span>20½-21″</span></td>
                                            <td><span>58-60″</span></td>
                                            <td><span>53-55″</span></td>
                                            <td><span>38-38½″</span></td>
                                        </tr>
                                        <tr class="oddrow">
                                            <td><span>5XL</span></td>
                                            <td><span>22-22½″</span></td>
                                            <td><span>62-64″</span></td>
                                            <td><span>58-60″</span></td>
                                            <td><span>38½-39"</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop Tab End -->


        </div>
        <div id="shopify-section-template--14758549553270__related-product" class="shopify-section">

            <div class="product-recommendations"
                data-url="/recommendations/products?section_id=template--14758549553270__related-product&product_id=6803175768182&limit=10&intent=related">
            </div>










        </div>
        <div id="shopify-section-template--14758549553270__recently-viewed-product" class="shopify-section">
            <script src="//ajax.aspnetcdn.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"
                type="text/javascript"></script>
            <script
                src="//new-99.myshopify.com/cdn/shop/t/88/assets/recently-view.js?v=171679271478575830981701360378"></script>

            <section class="recently-viewed-products" id="section-template--14758549553270__recently-viewed-product"
                data-section="RecentlyViewedProduct">
                <div class="container">
                    <div class="row" id="recently-viewed-products" style="display: none;">
                        <div class="col-lg-12">
                            <div class="section-title-area text-center">
                                <h2 class="section-title">Recent View</h2>
                                <p>Recently viewed products</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="">

                                <script id="recently-viewed-product-template" type="text/x-jquery-tmpl">
                                        <div class="recently-viewed-product product-item product-wrapper col-6 col-sm-6 col-md-4 col-lg-3">
                                            <div class="ltn__product-item ltn__product-item-3 text-center">
                                                <div class="product-img">
                                                    <a href="#">
                                                    <img src="" />
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                <h2 class="product-title">
                                                    <a href="#">${title}</a>
                                                </h2>
                                                <div class="product-price">
                                                        <del>1223</del>
                                                        <span>424244</span>
                                                        <span>2424244</span>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                </script>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>

                /* this code for currency support */
                window.show_multiple_currencies = true;
                window.shop_currency = "USD";
                window.money_format = "";

                /* recently viewed plugin api start */
                Shopify.Products.showRecentlyViewed({
                    howManyToShow: 4,
                    howManyToStoreInMemory: 10,
                    wrapperId: 'recently-viewed-products',
                    templateId: 'recently-viewed-product-template',
                    onComplete: function () {

                        if (window.currency_enable) {
                            /* this section ID to be your currency picker (Select) ID */
                            Currency.convertAll(window.shop_currency, jQuery('#currencies').val(), 'span.money', 'money_format');
                        }
                    }

                });
                /* recently viewed plugin api end */

                Shopify.Products.recordRecentlyViewed();

                setTimeout(function () {
                    $('.recently-viewed-product').parents('.recently-viewed-products').addClass('pb-50');
                }, 1000);
            </script>


        </div>
        <div id="shopify-section-template--14758549553270__f97449dc-f96f-40a9-b3fa-fe9edb9ef6de" class="shopify-section">
            <style data-shopify>
                #section-template--14758549553270__f97449dc-f96f-40a9-b3fa-fe9edb9ef6de {
                    padding-top: 0px;
                    padding-bottom: 80px;
                }

                @media (min-width: 768px) and (max-width: 991px) {
                    #section-template--14758549553270__f97449dc-f96f-40a9-b3fa-fe9edb9ef6de {
                        padding-top: 0px;
                        padding-bottom: 50px;
                    }
                }

                @media (max-width: 767px) {
                    #section-template--14758549553270__f97449dc-f96f-40a9-b3fa-fe9edb9ef6de {
                        padding-top: 0px;
                        padding-bottom: 40px;
                    }
                }
            </style>
            <div class="ltn__faq-area" id="section-template--14758549553270__f97449dc-f96f-40a9-b3fa-fe9edb9ef6de">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title-area text-center">
                                <h2 class="section-title">FAQs</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ltn__faq-inner ltn__faq-inner-2">
                                <div id="accordion_2">
                                    <!-- card -->
                                    <div class="card">
                                        <h6 class=" ltn__card-title" data-toggle="collapse" data-target="#collapse-1"
                                            aria-expanded="true" aria-controls="collapse-1">
                                            How to buy a product?
                                        </h6>
                                        <div id="collapse-1" class="collapse show" aria-labelledby="heading-1"
                                            data-parent="#accordion_2">
                                            <div class="card-body">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Scelerisque eleifend
                                                    donec pretium vulputate sapien nec sagittis. Proin libero nunc consequat
                                                    interdum. Condimentum lacinia quis vel eros donec ac. Mauris sit amet
                                                    massa vitae tortor. Quisque id diam vel quam elementum pulvinar. Gravida
                                                    in fermentum et sollicitudin ac orci phasellus. Facilisis gravida neque
                                                    convallis a cras semper. Non arcu risus quis varius quam quisque id.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <h6 class="collapsed ltn__card-title" data-toggle="collapse"
                                            data-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                            How can i make refund from your website?
                                        </h6>
                                        <div id="collapse-2" class="collapse " aria-labelledby="heading-2"
                                            data-parent="#accordion_2">
                                            <div class="card-body">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Scelerisque eleifend
                                                    donec pretium vulputate sapien nec sagittis. Proin libero nunc consequat
                                                    interdum. Condimentum lacinia quis vel eros donec ac. Mauris sit amet
                                                    massa vitae tortor. Quisque id diam vel quam elementum pulvinar. Gravida
                                                    in fermentum et sollicitudin ac orci phasellus. Facilisis gravida neque
                                                    convallis a cras semper. Non arcu risus quis varius quam quisque id.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <h6 class="collapsed ltn__card-title" data-toggle="collapse"
                                            data-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                            I am a new user. How should I start?
                                        </h6>
                                        <div id="collapse-3" class="collapse " aria-labelledby="heading-3"
                                            data-parent="#accordion_2">
                                            <div class="card-body">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Scelerisque eleifend
                                                    donec pretium vulputate sapien nec sagittis. Proin libero nunc consequat
                                                    interdum. Condimentum lacinia quis vel eros donec ac. Mauris sit amet
                                                    massa vitae tortor. Quisque id diam vel quam elementum pulvinar. Gravida
                                                    in fermentum et sollicitudin ac orci phasellus. Facilisis gravida neque
                                                    convallis a cras semper. Non arcu risus quis varius quam quisque id.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <h6 class="collapsed ltn__card-title" data-toggle="collapse"
                                            data-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                            Are my details secured?
                                        </h6>
                                        <div id="collapse-4" class="collapse " aria-labelledby="heading-4"
                                            data-parent="#accordion_2">
                                            <div class="card-body">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Scelerisque eleifend
                                                    donec pretium vulputate sapien nec sagittis. Proin libero nunc consequat
                                                    interdum. Condimentum lacinia quis vel eros donec ac. Mauris sit amet
                                                    massa vitae tortor. Quisque id diam vel quam elementum pulvinar. Gravida
                                                    in fermentum et sollicitudin ac orci phasellus. Facilisis gravida neque
                                                    convallis a cras semper. Non arcu risus quis varius quam quisque id.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <h6 class="collapsed ltn__card-title" data-toggle="collapse"
                                            data-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                                            How do I make payment by my credit card?
                                        </h6>
                                        <div id="collapse-5" class="collapse " aria-labelledby="heading-5"
                                            data-parent="#accordion_2">
                                            <div class="card-body">
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                                    tempor incididunt ut labore et dolore magna aliqua. Scelerisque eleifend
                                                    donec pretium vulputate sapien nec sagittis. Proin libero nunc consequat
                                                    interdum. Condimentum lacinia quis vel eros donec ac. Mauris sit amet
                                                    massa vitae tortor. Quisque id diam vel quam elementum pulvinar. Gravida
                                                    in fermentum et sollicitudin ac orci phasellus. Facilisis gravida neque
                                                    convallis a cras semper. Non arcu risus quis varius quam quisque id.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="need-support text-center mt-50">
                                    <h2>For furthermore help, contact with our support team.</h2>
                                    <div class="btn-wrapper mb-30">
                                        <a href="/pages/contact" class="theme-btn-1 btn">Contact Us</a>
                                    </div>
                                    <h3 class="animated fadeIn"><i class="fas fa-phone"></i> +0123-456-789</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

@endsection

@section('scripts')
<script src="{{asset('client/shop/t/88/assets/jquery.zoom-master.js')}}"></script>
<script>
    function productZoom() {
        $('.product-zoom')
            .css('cursor', 'crosshair')
            .zoom();
    };
    if ($(window).width() > 767) {
        productZoom();
    };
</script>

<script src="{{asset('client/shop/t/88/assets/jquery.fancybox-3.min.js')}}"></script>

<script>
    $(document).ready(function () {
        $('[data-fancybox="gallery"]').fancybox();
    });
</script>


<script>


    var selectCallback = function (variant, selector) {
        timber.productPage({
            money_format: "WS$ 5",
            variant: variant,
            selector: selector
        });



        if (variant) {



            // Current variant select+
            var form = jQuery('#' + selector.domIdPrefix).closest('form');
            for (var i = 0, length = variant.options.length; i < length; i++) {
                var val = variant.options[i].replace(/'/g, "&#039;");
                var radioButton = form.find(".swatch[data-option-index='" + i + "'] :radio[value='" + val + "']");
                if (radioButton.size()) {
                    radioButton.get(0).checked = true;
                }
            }













            /* -- code added for Inventory -- */
            var selectors = {
                variantInventory: '.variant-inventory'
            };
            var inventory_level = (inv_qty[variant.id]);
            if (inventory_level == 0) {
                $(selectors.variantInventory, this.$container).html('Out of stock').show();
            }
            else if (inventory_level > 100) {
                $(selectors.variantInventory, this.$container).html("In stock").show();
            }
            else {
                $(selectors.variantInventory, this.$container).html(inventory_level + ' left in stock').show();
            }
            /* - Inventory end - */

















            // Current sku pass
            $('.variant-sku').text(variant.sku);
        } else {
            $('.variant-sku').empty();
        }// product image zoom with variant
        if (variant && variant.featured_media) {
            jQuery('#ProductThumbs a[data-media-id="' + variant.featured_media.id + '"]').trigger('click');
        }
    };

    jQuery(function ($) {
        new Shopify.OptionSelectors('productSelect', {
            product: { "id": 6803175768182, "title": "W. Unit Price Product", "handle": "w-unit-price-product", "description": "\u003cp\u003eInteger ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent adipiscing. Phasellus ullamcorper ipsum rutrum nunc. Nunc nonummy metus. Vestibulum volutpat pretium libero. Cras id dui. Aenean ut eros et nisl sagittis vestibulum. Nullam nulla eros, ultricies sit amet, nonummy id, imperdiet feugiat, pede. Sed lectus. Donec mollis hendrerit risus. Phasellus nec sem in justo pellentesque facilisis. Etiam imperdiet imperdiet orci. Nunc nec neque. Phasellus leo dolor, tempus non, auctor et, hendrerit quis, nisi. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Maecenas malesuada.\u003c\/p\u003e\n\u003cp\u003ePraesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci, ac placerat dolor lectus quis orci. Phasellus consectetuer vestibulum elit. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Vestibulum fringilla pede sit amet augue. In turpis. Pellentesque posuere.\u003c\/p\u003e", "published_at": "2022-10-31T12:37:13-04:00", "created_at": "2022-10-31T12:37:13-04:00", "vendor": "Vendor J", "type": "Type J", "tags": ["gold", "gray", "magenta", "maroon", "white"], "price": 5300, "price_min": 5300, "price_max": 5300, "available": true, "price_varies": false, "compare_at_price": 5800, "compare_at_price_min": 5800, "compare_at_price_max": 5800, "compare_at_price_varies": false, "variants": [{ "id": 39890775277686, "title": "white", "option1": "white", "option2": null, "option3": null, "sku": "1510", "requires_shipping": true, "taxable": true, "featured_image": { "id": 30100987478134, "product_id": 6803175768182, "position": 1, "created_at": "2022-10-31T12:58:22-04:00", "updated_at": "2022-10-31T12:58:23-04:00", "alt": null, "width": 600, "height": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503", "variant_ids": [39890775277686] }, "available": true, "name": "W. Unit Price Product - white", "public_title": "white", "options": ["white"], "price": 5300, "weight": 0, "compare_at_price": 5800, "inventory_management": null, "barcode": "", "featured_media": { "alt": null, "id": 22364573335670, "position": 1, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503" } }, "requires_selling_plan": false, "unit_price": 5300, "unit_price_measurement": { "measured_type": "weight", "quantity_value": "100.0", "quantity_unit": "g", "reference_value": 100, "reference_unit": "g" }, "selling_plan_allocations": [], "quantity_rule": { "min": 1, "max": null, "increment": 1 } }, { "id": 39890775310454, "title": "gold", "option1": "gold", "option2": null, "option3": null, "sku": "1511", "requires_shipping": true, "taxable": true, "featured_image": { "id": 30100987445366, "product_id": 6803175768182, "position": 2, "created_at": "2022-10-31T12:58:22-04:00", "updated_at": "2022-10-31T12:58:23-04:00", "alt": null, "width": 600, "height": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/29.png?v=1667235503", "variant_ids": [39890775310454] }, "available": true, "name": "W. Unit Price Product - gold", "public_title": "gold", "options": ["gold"], "price": 5300, "weight": 0, "compare_at_price": 5800, "inventory_management": null, "barcode": "", "featured_media": { "alt": null, "id": 22364573368438, "position": 2, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/29.png?v=1667235503" } }, "requires_selling_plan": false, "unit_price": 5300, "unit_price_measurement": { "measured_type": "weight", "quantity_value": "100.0", "quantity_unit": "g", "reference_value": 100, "reference_unit": "g" }, "selling_plan_allocations": [], "quantity_rule": { "min": 1, "max": null, "increment": 1 } }, { "id": 39890775343222, "title": "gray", "option1": "gray", "option2": null, "option3": null, "sku": "1512", "requires_shipping": true, "taxable": true, "featured_image": { "id": 30100987379830, "product_id": 6803175768182, "position": 3, "created_at": "2022-10-31T12:58:22-04:00", "updated_at": "2022-10-31T12:58:23-04:00", "alt": null, "width": 600, "height": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/30.png?v=1667235503", "variant_ids": [39890775343222] }, "available": true, "name": "W. Unit Price Product - gray", "public_title": "gray", "options": ["gray"], "price": 5300, "weight": 0, "compare_at_price": 5800, "inventory_management": null, "barcode": "", "featured_media": { "alt": null, "id": 22364573401206, "position": 3, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/30.png?v=1667235503" } }, "requires_selling_plan": false, "unit_price": 5300, "unit_price_measurement": { "measured_type": "weight", "quantity_value": "100.0", "quantity_unit": "g", "reference_value": 100, "reference_unit": "g" }, "selling_plan_allocations": [], "quantity_rule": { "min": 1, "max": null, "increment": 1 } }, { "id": 39890775375990, "title": "magenta", "option1": "magenta", "option2": null, "option3": null, "sku": "1513", "requires_shipping": true, "taxable": true, "featured_image": { "id": 30100987412598, "product_id": 6803175768182, "position": 4, "created_at": "2022-10-31T12:58:22-04:00", "updated_at": "2022-10-31T12:58:23-04:00", "alt": null, "width": 600, "height": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/31.png?v=1667235503", "variant_ids": [39890775375990] }, "available": true, "name": "W. Unit Price Product - magenta", "public_title": "magenta", "options": ["magenta"], "price": 5300, "weight": 0, "compare_at_price": 5800, "inventory_management": null, "barcode": "", "featured_media": { "alt": null, "id": 22364573433974, "position": 4, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/31.png?v=1667235503" } }, "requires_selling_plan": false, "unit_price": 5300, "unit_price_measurement": { "measured_type": "weight", "quantity_value": "100.0", "quantity_unit": "g", "reference_value": 100, "reference_unit": "g" }, "selling_plan_allocations": [], "quantity_rule": { "min": 1, "max": null, "increment": 1 } }, { "id": 39890775408758, "title": "maroon", "option1": "maroon", "option2": null, "option3": null, "sku": "1514", "requires_shipping": true, "taxable": true, "featured_image": { "id": 30100987510902, "product_id": 6803175768182, "position": 5, "created_at": "2022-10-31T12:58:22-04:00", "updated_at": "2022-10-31T12:58:24-04:00", "alt": null, "width": 600, "height": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/32.png?v=1667235504", "variant_ids": [39890775408758] }, "available": true, "name": "W. Unit Price Product - maroon", "public_title": "maroon", "options": ["maroon"], "price": 5300, "weight": 0, "compare_at_price": 5800, "inventory_management": null, "barcode": "", "featured_media": { "alt": null, "id": 22364573466742, "position": 5, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/32.png?v=1667235504" } }, "requires_selling_plan": false, "unit_price": 5300, "unit_price_measurement": { "measured_type": "weight", "quantity_value": "100.0", "quantity_unit": "g", "reference_value": 100, "reference_unit": "g" }, "selling_plan_allocations": [], "quantity_rule": { "min": 1, "max": null, "increment": 1 } }], "images": ["\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503", "\/\/new-99.myshopify.com\/cdn\/shop\/products\/29.png?v=1667235503", "\/\/new-99.myshopify.com\/cdn\/shop\/products\/30.png?v=1667235503", "\/\/new-99.myshopify.com\/cdn\/shop\/products\/31.png?v=1667235503", "\/\/new-99.myshopify.com\/cdn\/shop\/products\/32.png?v=1667235504"], "featured_image": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503", "options": ["Color"], "media": [{ "alt": null, "id": 22364573335670, "position": 1, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503" }, "aspect_ratio": 1.0, "height": 600, "media_type": "image", "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/28.png?v=1667235503", "width": 600 }, { "alt": null, "id": 22364573368438, "position": 2, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/29.png?v=1667235503" }, "aspect_ratio": 1.0, "height": 600, "media_type": "image", "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/29.png?v=1667235503", "width": 600 }, { "alt": null, "id": 22364573401206, "position": 3, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/30.png?v=1667235503" }, "aspect_ratio": 1.0, "height": 600, "media_type": "image", "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/30.png?v=1667235503", "width": 600 }, { "alt": null, "id": 22364573433974, "position": 4, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/31.png?v=1667235503" }, "aspect_ratio": 1.0, "height": 600, "media_type": "image", "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/31.png?v=1667235503", "width": 600 }, { "alt": null, "id": 22364573466742, "position": 5, "preview_image": { "aspect_ratio": 1.0, "height": 600, "width": 600, "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/32.png?v=1667235504" }, "aspect_ratio": 1.0, "height": 600, "media_type": "image", "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/32.png?v=1667235504", "width": 600 }], "requires_selling_plan": false, "selling_plan_groups": [], "content": "\u003cp\u003eInteger ante arcu, accumsan a, consectetuer eget, posuere ut, mauris. Praesent adipiscing. Phasellus ullamcorper ipsum rutrum nunc. Nunc nonummy metus. Vestibulum volutpat pretium libero. Cras id dui. Aenean ut eros et nisl sagittis vestibulum. Nullam nulla eros, ultricies sit amet, nonummy id, imperdiet feugiat, pede. Sed lectus. Donec mollis hendrerit risus. Phasellus nec sem in justo pellentesque facilisis. Etiam imperdiet imperdiet orci. Nunc nec neque. Phasellus leo dolor, tempus non, auctor et, hendrerit quis, nisi. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Maecenas malesuada.\u003c\/p\u003e\n\u003cp\u003ePraesent congue erat at massa. Sed cursus turpis vitae tortor. Donec posuere vulputate arcu. Phasellus accumsan cursus velit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Sed aliquam, nisi quis porttitor congue, elit erat euismod orci, ac placerat dolor lectus quis orci. Phasellus consectetuer vestibulum elit. Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Vestibulum fringilla pede sit amet augue. In turpis. Pellentesque posuere.\u003c\/p\u003e" },
            onVariantSelected: selectCallback,
            enableHistoryState: true
        });

        // Add label if only one product option and it isn't 'Title'. Could be 'Size'.

        $('.selector-wrapper:eq(0)').prepend('<label for="productSelect-option-0">Color</label>');


        // Hide selectors if we only have 1 variant and its title contains 'Default'.

    });
</script>
<script>
    function setupShopifyXr() {
        if (!window.ShopifyXR) {
            document.addEventListener('shopify_xr_initialized', function () {
                setupShopifyXr();
            });
        } else {

            window.ShopifyXR.addModels([]);
            window.ShopifyXR.setupXRElements();
        }
    }

    window.Shopify.loadFeatures([
        {
            name: 'shopify-xr',
            version: '1.0',
            onLoad: setupShopifyXr
        }
    ]);
</script>

<!-- <script>
    window.ShopifyXR.launchXR({
        model3dId: 1,
        title: "W. Unit Price Product",
    });
</script> -->
<script>
    $('[data-countdown].ltn__countdown-2').each(function () {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        if (!$this.hasClass('countdown-full-format')) {
            $this.countdown(finalDate, function (event) {
                $this.html(event.strftime('<div class="single"><h6>%D</h6><p>Days</p></div> <div class="single"><h6>%H</h6><p>Hrs</p></div> <div class="single"><h6>%M</h6><p>Mins</p></div> <div class="single"><h6>%S</h6><p>Secs</p></div>'));
            });
        } else {
            $this.countdown(finalDate, function (event) {
                $this.html(event.strftime('<div class="single"><h6>%Y</h6><p>Years</p></div> <div class="single"><h6>%m</h6><p>Months</p></div> <div class="single"><h6>%W</h6><p>Weeks</p></div> <div class="single"><h6>%d</h6><p>Days</p></div> <div class="single"><h6>%H</h6><p>Hrs</p></div> <div class="single"><h6>%M</h6><p>Mins</p></div> <div class="single"><h6>%S</h6><p>Secs</p></div>'));
            });
        }
    });
</script>
@endsection