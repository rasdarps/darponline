@extends('client_layout.main')
@section('breadcrumb')
    <div id="shopify-section-breadcrumb" class="shopify-section">
        <!-- BREADCRUMB AREA START -->
        <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white plr--9 breadcrumbs overlay-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2">
                            <div class="section-title-area ltn__section-title-2">
                                <h1 class="breadcrumbs-title section-title white-color">Wishlist</h1>
                                <div class="ltn__breadcrumb-list">
                                    <ul class="breadcrumb-list">
                                        <li>
                                            <a href="/" title="Back to the home page">Home</a>
                                        </li>
                                        <li>



                                            <span>Wishlist</span>


                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BREADCRUMB AREA END -->

        <style>
            /* Breadcurmb Banner */
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
    <div id="shopify-section-template--14758549454966__main" class="shopify-section">
        <div class="wishlist customer-page mb-50" id="wishlist">
            <div class="container">
                <div class="wishlist-page">
                    <div class="row" grid-wishlist>

                    </div>
                    <div class="row wishlist-grid--empty-list">
                        <div class="col-12">
                            <div class="empty-list--info">
                                <h1 class="empty-list--text black">Your wishlist is currently empty!</h1>
                                <p class="">Continue browsing <a href="/collections/all">here</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style data-shopify>
            #section-template--14758549454966__main {}

            @media (min-width: 768px) and (max-width: 991px) {
                #section-template--14758549454966__main {}
            }

            @media (max-width: 767px) {
                #section-template--14758549454966__main {}
            }
        </style>
        <style>
            .product-tile--tile-actions a {
                width: 100%;
            }
        </style>

    </div>
@endsection